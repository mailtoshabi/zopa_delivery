<?php

namespace App\Http\Controllers\Front;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\Addon;
use App\Models\AddonWallet;
use App\Models\Customer;
use App\Models\Meal;
use App\Models\MealWallet;
use App\Models\CustomerOrder;
use App\Models\DailyAddon;
use App\Models\DailyMeal;
use App\Models\MealLeave;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

use Razorpay\Api\Api;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Display all the meals on the meal plan page.
     */

    protected $cutoffHour;
    protected $cutoffMinute;

    public function __construct()
    {
        $cutoff = Utility::getCutoffHourAndMinute();
        $this->cutoffHour = $cutoff['hour'];
        $this->cutoffMinute = $cutoff['minute'];
    }

     public function getDistrictList(Request $request)
    {
        $districts = DB::table('districts')
            ->where('state_id', $request->s_id)
            ->orderBy('name', 'asc')
            ->select('id', 'name')
            ->get();

        $data[] = '<option value="">Select District</option>';

        foreach ($districts as $district) {
            $selected = $district->id == $request->d_id ? 'selected' : '';
            $data[] = '<option value="' . $district->id . '"' . $selected . '>' . $district->name . '</option>';
        }

        return $data;
    }

    public function showMealPlans()
    {
        $meals = Meal::with(['ingredients', 'remarks'])
                ->where('status', Utility::ITEM_ACTIVE)
                ->where('id', '!=', Utility::SINGLE_MEAL_ID)
                ->get();

        return view('pages.meal_plan', compact('meals'));
    }

    public function showSingleMeal()
    {
        $meal = Meal::with(['ingredients', 'remarks'])
                ->where('status', Utility::ITEM_ACTIVE)
                ->where('id', Utility::SINGLE_MEAL_ID)
                ->first();

        return view('pages.single_meal', compact('meal'));
    }

    public function showMealPurchasePage($encryptedMealId)
    {
        try {
            $mealId = Crypt::decrypt($encryptedMealId);
            $meal = Meal::with(['ingredients', 'remarks'])->findOrFail($mealId);
            $addons = Addon::where('status', Utility::ITEM_ACTIVE)->get();

            return view('pages.meal_purchase', compact('meal','addons'));

        } catch (DecryptException $e) {
            abort(404); // or redirect with error
        }
    }

    public function purchaseMeal(Request $request, $meal_id)
    {
        $invoiceNo = $this->generateInvoiceNumber();
        $meal = Meal::findOrFail(decrypt($meal_id));

        $validated = $request->validate([
            'addons' => 'nullable|array',
            'addons.*.quantity' => 'nullable|integer|min:1',
            'pay_method' => 'required|in:1,2',  // 1 = Online, 2 = Cash on Delivery
        ]);

        $customer = Auth::guard('customer')->user();
        $payMethod = $validated['pay_method'];

        // $grandTotal = 0;

        // Create the parent order
        $customerOrder = CustomerOrder::create([
            'invoice_no' => $invoiceNo,
            'customer_id' => $customer->id,
            'pay_method' => $payMethod,
            'discount' => 0,
            'delivery_charge' => 0,
            'amount' => 0, // temporary, will update later
            'ip_address' => $request->ip(),
            'is_paid' => 0,
            'status' => 1,
        ]);

        // Attach the meal
        $customerOrder->meals()->create([
            'meal_id' => $meal->id,
            'price' => $meal->price,
            'quantity' => $meal->quantity,
        ]);

        // Attach addons if provided
        if (!empty($validated['addons'])) {
            $addonIds = array_keys($validated['addons']);
            $addons = Addon::whereIn('id', $addonIds)->get()->keyBy('id');

            foreach ($validated['addons'] as $addonId => $addonData) {
                if (!isset($addons[$addonId])) continue;

                $customerOrder->addons()->create([
                    'addon_id' => $addonId,
                    'quantity' => $addonData['quantity'],
                    'price' => $addons[$addonId]->price,
                ]);
            }
        }

        $grandTotal = $customerOrder->calculateTotal();

        // Update total amount
        $customerOrder->update(['amount' => $grandTotal]);

        // Razorpay payment
        if ($payMethod == Utility::PAYMENT_ONLINE) {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $razorpayOrder = $api->order->create([
                'receipt' => $invoiceNo,
                'amount' => $grandTotal * 100,
                'currency' => 'INR',
                'payment_capture' => 1,
                'notes'           => [
                    'invoice_no' => $customerOrder->invoice_no,
                    'customer_name' => $customerOrder->customer->name,
                ]
            ]);

            // Save Razorpay order ID in DB
            $customerOrder->update([
                'razorpay_order_id' => $razorpayOrder['id']
            ]);

            // Also store in session (optional)
            Session::put('razorpay_order_id', $razorpayOrder['id']);
            Session::put('customer_order_id', $customerOrder->id);

            return view('pages.razorpay_payment', [
                'order' => $razorpayOrder,
                'customer' => $customer,
                'razorpayKey' => config('services.razorpay.key'),
                'grandTotal' => $grandTotal,
            ]);
        }

        return redirect()->route('meal.payment.success', encrypt($customerOrder->id));
    }

    public function showMealPaymentSuccess($orderId)
    {
        $orderId=decrypt($orderId);
        $customer = Auth::guard('customer')->user();
        $customerOrder = CustomerOrder::where('id', $orderId)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        // Load meals attached to the order
        $mealsPivot = $customerOrder->meals()->with('meal')->get();
        $addonsPivot = $customerOrder->addons()->with('addon')->get();

        return view('pages.payment_success', [
            'customerOrder' => $customerOrder,
            'meals' => $mealsPivot,
            'addons' => $addonsPivot,
            'payment_method' => $customerOrder->pay_method,
        ]);
    }

    public function showAddons()
    {
        $addons = Addon::where('status', Utility::ITEM_ACTIVE)->get();

        return view('pages.buy_addons', compact('addons'));
    }

    public function showAddonPurchasePage(Request $request)
    {
        $validated = $request->validate([
            'addons' => 'required|array',
            // 'addons.*.quantity' => 'required|integer|min:1',
            // 'submit_action' => 'required|in:add_to_cart,checkout',
        ], [
            'addons.required' => 'Click on the addons to select',
            // 'addons.*.quantity.required' => 'Please enter quantity for selected addon.',
            // 'addons.*.quantity.integer' => 'Quantity must be a number.',
            'addons.*.quantity.min' => 'Quantity must be at least 1.',
        ]);

        $addonIds = array_keys(array_filter($validated['addons'], fn($a) => !empty($a['quantity'])));

        if (empty($addonIds)) {
            return redirect()->back()->withErrors(['Please select at least one addon.']);
        }

        $addons = Addon::whereIn('id', $addonIds)->get()->map(function ($addon) use ($validated) {
            $addon->selected_quantity = $validated['addons'][$addon->id]['quantity'];
            return $addon;
        });

        // if ($request->submit_action === 'add_to_cart') {
        //     $cart = session()->get('addon_cart', []);

        //     foreach ($addons as $addon) {
        //         if (isset($cart[$addon->id])) {
        //             $cart[$addon->id]['quantity'] += $addon->selected_quantity;
        //         } else {
        //             $cart[$addon->id] = [
        //                 'name' => $addon->name,
        //                 'price' => $addon->price,
        //                 'quantity' => $addon->selected_quantity,
        //             ];
        //         }
        //     }

        //     session()->put('addon_cart', $cart);

        //     return redirect()->route('cart.index')->with('success', 'Addons added to cart!');
        // }

        // if ($request->submit_action === 'checkout') {
            return view('pages.addon_purchase', compact('addons'));
        // }

        abort(400); // Should not reach here
    }

    public function addonPurchase(Request $request)
    {

        $invoiceNo = $this->generateInvoiceNumber();
        $validated = $request->validate([
            'addons' => 'required|array',
            'addons.*.quantity' => 'required|integer|min:1',
            'pay_method' => 'required|in:' . implode(',', [Utility::PAYMENT_ONLINE, Utility::PAYMENT_COD]),
        ], [
            'addons.*.quantity.required' => 'Please enter quantity for selected addon.',
            'addons.*.quantity.integer' => 'Quantity must be a number.',
            'addons.*.quantity.min' => 'Quantity must be at least 1.',
        ]);
        // return "Hello";

        $addonIds = array_keys($validated['addons']);
        $addons = Addon::whereIn('id', $addonIds)->get()->keyBy('id');

        if ($addons->isEmpty()) {
            return redirect()->route('addons.buy')->withErrors(['Invalid addon selection.']);
        }

        $customer = Auth::guard('customer')->user();
        $payMethod = $validated['pay_method'];

        // Step 1. Create CustomerOrder (the parent order)
        $customerOrder = CustomerOrder::create([
            'invoice_no' => $invoiceNo,
            'customer_id' => $customer->id,
            'pay_method' => $payMethod,
            'discount' => 0,              // No discount for single meal (adjust if needed)
            'delivery_charge' => 0,       // No delivery charge (adjust if needed)
            'amount' => 0, // temporary, will update later
            'ip_address' => $request->ip(),
            'is_paid' => 0,               // Not paid yet
            'status' => 0,                // Active
        ]);

        foreach ($validated['addons'] as $addonId => $addonData) {
            if (!isset($addons[$addonId])) {
                continue;
            }
            // Step 2. Attach the addons into customer_addon (under the created order)
            $customerOrder->addons()->create([
                'addon_id' => $addonId,
                'quantity' => $addonData['quantity'],
                'price' => $addons[$addonId]->price,
            ]);
        }

        $grandTotal = $customerOrder->calculateTotal();

        // Update total amount
        $customerOrder->update(['amount' => $grandTotal]);

        // Razorpay payment
        if ($payMethod == Utility::PAYMENT_ONLINE) {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $razorpayOrder = $api->order->create([
                'receipt' => $invoiceNo,
                'amount' => $grandTotal * 100,
                'currency' => 'INR',
                'payment_capture' => 1,
                'notes'           => [
                    'invoice_no' => $customerOrder->invoice_no,
                    'customer_name' => $customerOrder->customer->name,
                ]
            ]);

            // Save Razorpay order ID in DB
            $customerOrder->update([
                'razorpay_order_id' => $razorpayOrder['id']
            ]);

            // Also store in session (optional)
            Session::put('razorpay_order_id', $razorpayOrder['id']);
            Session::put('customer_order_id', $customerOrder->id);

            return view('pages.razorpay_payment', [
                'order' => $razorpayOrder,
                'customer' => $customer,
                'razorpayKey' => config('services.razorpay.key'),
                'grandTotal' => $grandTotal,
            ]);
        }

        // Show confirmation
        // return view('pages.addon_payment_success', [
        //     'addons' => $addons,
        //     'customerOrder'=>$customerOrder,
        //     'quantities' => $validated['addons'],
        //     'payment_method' => $validated['pay_method'],
        // ]);
        return redirect()->route('addons.payment.success', encrypt($customerOrder->id));
    }

    public function showAddonPaymentSuccess($orderId)
    {
        $orderId = decrypt($orderId);
        $customer = Auth::guard('customer')->user();
        $customerOrder = CustomerOrder::where('id', $orderId)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        // Load addons with pivot data (quantity + price)
        $addonsPivot = $customerOrder->addons()->with('addon')->get();

        // Rebuild quantities array: [addon_id => ['quantity' => X]]
        $quantities = [];
        foreach ($addonsPivot as $pivot) {
            $quantities[$pivot->addon_id] = ['quantity' => $pivot->quantity];
        }

        return view('pages.addon_payment_success', [
            'customerOrder' => $customerOrder,
            'addons' => $addonsPivot,
            'quantities' => $quantities,
            'payment_method' => $customerOrder->pay_method,
        ]);
    }

    public function generateInvoiceNumber()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Count in CustomerMeal
        $totalCount = CustomerOrder::whereMonth('created_at', $currentMonth)
                        ->whereYear('created_at', $currentYear)
                        ->count();

        // Next invoice number
        $nextNumber = $totalCount + 1;

        $invoice = 'ZP-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT) . '-' . date('m/Y');

        return $invoice;
    }

    public function myPurchases(Request $request)
    {
        $customer = auth('customer')->user();

        $purchases = CustomerOrder::where('customer_id', $customer->id)
            ->latest()
            ->paginate(Utility::LOAD_MORE_COUNT); // Example: 5 or 10

        if ($request->ajax()) {
            return view('partials.purchases_list', compact('purchases'))->render();
        }

        return view('pages.purchases', compact('purchases'));
    }

    public function myWallet()
    {
        $customerId = Auth::guard('customer')->id();

        // Meal Wallet
        $meal_wallet = MealWallet::where('customer_id', $customerId)->first();
        $mealsLeft = $meal_wallet ? $meal_wallet->quantity : 0;

        // Addon Wallet — fetch addon_wallet records where status is active
        $addon_wallet = AddonWallet::where('customer_id', $customerId)
                        // ->where('status', Utility::ITEM_ACTIVE)
                        ->where('quantity', '>', 0)
                        ->with('addon') // eager load addon details (name, price, image)
                        ->get();

        $now = now();
        $today = $now->copy()->startOfDay();
        $cutoffTime = $today->copy()->setTime($this->cutoffHour, $this->cutoffMinute);

        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $monthlyLeaveCount = MealLeave::where('customer_id', $customerId)
        ->whereBetween('leave_at', [$monthStart, $monthEnd])
        ->count();

        $maxLeaves = Utility::MAX_MONTHLY_LEAVES;

        if ($now->lessThanOrEqualTo($cutoffTime)) {
            $activeLeaveCount = MealLeave::where('customer_id', $customerId)
                ->whereDate('leave_at', '>=', $today)
                ->count();
        } else {
            $activeLeaveCount = MealLeave::where('customer_id', $customerId)
                ->whereDate('leave_at', '>', $today)
                ->count();
        }

        $maxActiveLeaves = Utility::MAX_ACTIVE_LEAVES;

        return view('pages.my_wallet', compact('mealsLeft', 'meal_wallet', 'addon_wallet', 'monthlyLeaveCount', 'maxLeaves','activeLeaveCount','maxActiveLeaves'));
    }



    // public function dailyOrders(Request $request)
    // {
    //     $customer = auth('customer')->user();

    //     if ($request->ajax()) {
    //         if ($request->tab === 'previous') {
    //             $previousOrders = DailyMeal::with('dailyAddons.addon')->where('customer_id', $customer->id)
    //                 ->whereDate('date', '<', Carbon::today())
    //                 ->orderByDesc('id')
    //                 ->paginate(Utility::LOAD_MORE_COUNT);

    //             if ($request->has('load_more')) {
    //                 return view('partials.load_more_orders', compact('previousOrders'))->render();
    //             }

    //             return view('partials.previous_orders', compact('previousOrders'))->render();
    //         }

    //         if ($request->tab === 'coming') {
    //             $comingOrders = DailyMeal::with('dailyAddons.addon')->where('customer_id', $customer->id)
    //                 ->whereDate('date', '>', Carbon::today())
    //                 ->orderBy('date')
    //                 ->get();

    //             return view('partials.coming_orders', compact('comingOrders'));
    //         }

    //         $todaysOrders = DailyMeal::with('dailyAddons.addon')->where('customer_id', $customer->id)
    //             ->whereDate('date', Carbon::today())
    //             ->orderByDesc('id')
    //             ->get();

    //         return view('partials.todays_orders', compact('todaysOrders'));
    //     }

    //     return view('pages.daily_meals');
    // }

    public function dailyMeals()
    {
        $customerId = auth('customer')->id();
        $customer = Customer::findOrFail($customerId);
        // $today = Carbon::today();
        $today = Carbon::today()->toDateString(); // e.g. "2025-05-12"
        $cutoffTime = now()->setTime($this->cutoffHour, $this->cutoffMinute);
        $todayOrders = DailyMeal::with('dailyAddons.addon')
            ->where('customer_id', $customerId)
            ->where('status', Utility::ITEM_ACTIVE)
            ->whereDate('date', $today)
            ->orderBy('id','desc')
            ->get();

        $hasLeaveToday = $customer->mealLeaves()
        ->whereDate('leave_at', Carbon::today())
        ->exists();

        $meal_wallet = MealWallet::where('customer_id', $customerId)->first();
        $mealsLeft = $meal_wallet ? $meal_wallet->quantity : 0;

        return view('pages.daily_meals', [
            'type' => 'daily',
            'todayOrders' => $todayOrders,
            'cutoffTime' => $cutoffTime,
            'mealsLeft' => $mealsLeft,
            'hasLeaveToday' => $hasLeaveToday,
        ]);
    }

    public function extraMeals()
    {
        $customerId = auth('customer')->id();
        // $today = Carbon::today();
        $today = Carbon::today()->toDateString(); // e.g. "2025-05-12"
        $cutoffTime = now()->setTime($this->cutoffHour, $this->cutoffMinute);

        $extraOrders = DailyMeal::with('dailyAddons.addon')
            ->where('customer_id', $customerId)
            ->where('is_auto', Utility::ITEM_INACTIVE)
            ->whereDate('date', '>=', $today)   // include today
            ->orderBy('id','desc')
            ->get();

        return view('pages.extra_meals', [
            'type' => 'extra',
            'extraOrders' => $extraOrders,
            'cutoffTime' => $cutoffTime
        ]);
    }

    public function cancelDailyOrder(Request $request, $id)
    {
        $customer = auth('customer')->user();

        $dailyMeal = DailyMeal::with('dailyAddons')->where('id', $id)
            ->where('customer_id', $customer->id)
            ->where('status', Utility::ITEM_ACTIVE)
            ->firstOrFail();

        $currentTime = now();
        $cutoffTime = now()->setTime($this->cutoffHour, $this->cutoffMinute);

        if ($dailyMeal->date->isToday() && $currentTime->greaterThan($cutoffTime)) {
            $message = 'Cancellation is only allowed before ' . FileHelper::convertTo12Hour(Utility::CUTOFF_TIME);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $message
                ], 403);
            }

            return back()->with('error', $message);
        }

        // Cancel meal
        $dailyMeal->update(['status' => Utility::ITEM_INACTIVE]);

        // Refund MEAL quantity
        MealWallet::updateOrCreate(
            ['customer_id' => $customer->id, 'status' => Utility::ITEM_ACTIVE],
            ['quantity' => DB::raw('quantity + ' . $dailyMeal->quantity)]
        );

        // Refund ADDON quantities (if any)
        if ($dailyMeal->dailyAddons->isNotEmpty()) {
            foreach ($dailyMeal->dailyAddons as $addonItem) {
                AddonWallet::updateOrCreate(
                    ['customer_id' => $customer->id, 'addon_id' => $addonItem->addon_id, 'status' => Utility::ITEM_ACTIVE],
                    ['quantity' => DB::raw('quantity + ' . $addonItem->quantity)]
                );
            }
        }

        $message = 'Order cancelled and refunded to wallet.';

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }

    public function requestExtraMeal(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $customer = auth('customer')->user();

        if (!$customer->status || !$customer->is_approved) {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is not active or approved to request meals.'
            ], 403);
        }

        $wallet = $customer->mealWallet;

        if (!$wallet || $wallet->quantity < $request->quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient meals in your wallet.'
            ], 422);
        }

        $requestedDate = Carbon::parse($request->date);

        // ❌ Disallow Sunday requests
        if ($requestedDate->isSunday()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sunday is a holiday. Extra meals cannot be requested.'
            ], 422);
        }

        $today = now()->startOfDay();

        if ($requestedDate->equalTo($today)) {
            $cutoffTime = now()->copy()->setTime($this->cutoffHour, $this->cutoffMinute);

            if (now()->greaterThan($cutoffTime)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Extra meal requests for today are only allowed before ' . FileHelper::convertTo12Hour(Utility::CUTOFF_TIME)
                ], 422);
            }
        }

        DB::beginTransaction();

        try {
            $wallet->decrement('quantity', $request->quantity);
            $wallet->refresh();

            $dailyMeal = DailyMeal::create([
                'customer_id' => $customer->id,
                'quantity' => $request->quantity,
                'date' => $request->date,
                'status' => Utility::ITEM_ACTIVE,
                'is_auto' => 0
            ]);

            $skipAddons = $request->boolean('skip_addons');

            if (!$skipAddons) {
                $addonWallets = AddonWallet::where('customer_id', $customer->id)
                    ->where('quantity', '>=', $request->quantity)
                    ->where('is_on', Utility::ITEM_ACTIVE)
                    ->get();

                foreach ($addonWallets as $addonWallet) {
                    $addonWallet->decrement('quantity', $request->quantity);
                    $addonWallet->refresh();

                    DailyAddon::create([
                        'daily_meal_id' => $dailyMeal->id,
                        'addon_id' => $addonWallet->addon_id,
                        'quantity' => $request->quantity,
                        'status' => Utility::ITEM_ACTIVE
                    ]);
                }
            }

            DB::commit();

            $message = !$skipAddons
                ? (isset($addonWallets) && count($addonWallets) > 0
                    ? 'Extra meal(s) and available addons added successfully.'
                    : 'Extra meal(s) added successfully. No addons available today.')
                : 'Extra meal(s) added successfully without addons.';

            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }


public function mealLeaves()
{
    $customerId = Auth::guard('customer')->id();

    $now = now();
    $today = $now->copy()->startOfDay();
    $cutoffTime = $today->copy()->setTime($this->cutoffHour, $this->cutoffMinute);

    $monthStart = $now->copy()->startOfMonth();
    $monthEnd = $now->copy()->endOfMonth();

    // 🔁 Only show leaves from current month onwards
    $leaves = MealLeave::where('customer_id', $customerId)
        ->whereDate('leave_at', '>=', $monthStart)
        ->orderBy('leave_at', 'desc')
        ->get();

    $monthlyLeaveCount = MealLeave::where('customer_id', $customerId)
        ->whereBetween('leave_at', [$monthStart, $monthEnd])
        ->count();

    $maxLeaves = Utility::MAX_MONTHLY_LEAVES;

    if ($now->lessThanOrEqualTo($cutoffTime)) {
        $activeLeaveCount = MealLeave::where('customer_id', $customerId)
            ->whereDate('leave_at', '>=', $today)
            ->count();
    } else {
        $activeLeaveCount = MealLeave::where('customer_id', $customerId)
            ->whereDate('leave_at', '>', $today)
            ->count();
    }

    $maxActiveLeaves = Utility::MAX_ACTIVE_LEAVES;

    return view('pages.meal_leaves', compact(
        'leaves',
        'monthlyLeaveCount',
        'maxLeaves',
        'activeLeaveCount',
        'maxActiveLeaves'
    ));
}

public function markLeaves(Request $request)
{
    $request->validate([
        'date' => 'required|date|after_or_equal:today',
    ]);

    $date = Carbon::parse($request->date);
    $now = now();
    $today = $now->copy()->startOfDay();
    $cutoffTime = $now->copy()->setTime($this->cutoffHour, $this->cutoffMinute, 0);

    // ✅ Allow leave only for next specific days
    $maxAllowedDate = $now->copy()->addDays(Utility::MAX_LEAVE_DAYS_AHEAD);
    if ($date->greaterThan($maxAllowedDate)) {
        return redirect()->back()->with('error', 'You can only mark leave for the next '. Utility::MAX_LEAVE_DAYS_AHEAD .' days.');
    }

    if ($date->isSunday()) {
        return redirect()->back()->with('error', "Sundays are already off. You don't need to mark leave.");
    }

    // ✅ Prevent marking leave for today after cutoff time
    if ($date->isToday()) {
        if ($now->format('H:i') > $cutoffTime->format('H:i')) {
            return redirect()->back()->with('error', 'Cannot mark leave for today after ' . FileHelper::convertTo12Hour(Utility::CUTOFF_TIME));
        }
    }

    $customer = auth('customer')->user();

    // ✅ Per-month leave limit
    $leaveMonthStart = $date->copy()->startOfMonth();
    $leaveMonthEnd = $date->copy()->endOfMonth();

    $monthlyLeaveCount = MealLeave::where('customer_id', $customer->id)
        ->whereBetween('leave_at', [$leaveMonthStart, $leaveMonthEnd])
        ->count();

    if ($monthlyLeaveCount >= Utility::MAX_MONTHLY_LEAVES) {
        return redirect()->back()->with('error', 'You can only mark up to ' . Utility::MAX_MONTHLY_LEAVES . ' leaves in ' . $date->format('F') . '.');
    }

    // ✅ Max 5 active leaves at any time
    $activeLeaveCount = MealLeave::where('customer_id', $customer->id)
        ->whereDate('leave_at', '>=', today())
        ->count();

    if ($now->lessThanOrEqualTo($cutoffTime)) {
        $activeLeaveCount = MealLeave::where('customer_id', $customer->id)
            ->whereDate('leave_at', '>=', $today)
            ->count();
    } else {
        $activeLeaveCount = MealLeave::where('customer_id', $customer->id)
            ->whereDate('leave_at', '>', $today)
            ->count();
    }

    if ($activeLeaveCount >= Utility::MAX_ACTIVE_LEAVES) {
        return redirect()->back()->with('error', 'You can only have up to ' . Utility::MAX_ACTIVE_LEAVES . ' active leaves at a time.');
    }

    // ✅ Prevent duplicate leave
    $alreadyExists = MealLeave::where('customer_id', $customer->id)
        ->whereDate('leave_at', $date)
        ->exists();

    if ($alreadyExists) {
        return redirect()->back()->with('error', 'You have already marked leave for this date.');
    }

    MealLeave::create([
        'customer_id' => $customer->id,
        'leave_at' => $date,
    ]);

    return redirect()->back()->with('success', 'Leave marked successfully.');
}

public function destroyLeave($id)
{
    $customer = auth('customer')->user();
    $leave = MealLeave::where('id', $id)
        ->where('customer_id', $customer->id)
        ->firstOrFail();

    $now = now();
    $leaveDate = Carbon::parse($leave->leave_at);

    if ($leaveDate->isToday()) {
        // Create a Carbon time today using only cutoff hour & minute
        $cutoffTime = now()->copy()->setTime($this->cutoffHour, $this->cutoffMinute, 0);

        // Compare only time (hour & minute)
        if ($now->format('H:i') > $cutoffTime->format('H:i')) {
            return redirect()->back()->with('error', 'Cannot cancel today\'s leave after ' . FileHelper::convertTo12Hour(Utility::CUTOFF_TIME));
        }
    }

    if ($leaveDate->isPast() && !$leaveDate->isToday()) {
        return redirect()->back()->with('error', 'Cannot cancel past leave.');
    }

    $leave->delete();

    return redirect()->back()->with('success', 'Leave cancelled successfully.');
}

public function toggleStatus(Request $request)
{
    $request->validate([
        'wallet_id' => 'required|exists:addon_wallet,id',
        'is_on' => 'required|boolean',
    ]);

    $wallet = AddonWallet::findOrFail($request->wallet_id);

    if ($wallet->customer_id !== auth('customer')->id()) {
        return response()->json(['success' => false], 403);
    }

    $wallet->is_on = $request->is_on;
    $wallet->save();

    return response()->json(['success' => true]);
}

public function about_us()
    {
        return view('pages.about_us');
    }
public function payment_terms()
    {
        return view('pages.payment_terms');
    }
public function privacy_policy()
    {
        return view('pages.privacy_policy');
    }
public function support()
    {
        return view('pages.support');
    }
public function faq()
    {
        return view('pages.faq');
    }
public function profile()
    {
        $customer = auth('customer')->user();
        $states = DB::table('states')->orderBy('name', 'asc')->select('id', 'name')->get();
        return view('pages.profile',compact('customer','states'));
    }

    // In the CustomerController.php

public function updateProfile(Request $request)
{

    $customer = Customer::findOrFail(auth('customer')->id());

    // Validation rules
    $rules = [
        'name' => 'required|string|max:255',
        'office_name' => 'required|string|max:255',
        'designation' => 'nullable|string|max:255',
        'whatsapp' => 'nullable|string|max:15',
        'city' => 'required|string|max:255',
        'landmark' => 'nullable|string|max:255',
        'postal_code' => 'required|string|max:6',
        'image' => 'nullable',
        // 'state_id' => 'required|exists:states,id',
        // 'district_id' => 'required|exists:districts,id',
    ];

    // Custom messages
    $messages = [
        'name.required' => 'Please enter your full name.',
        'name.max' => 'Name should not exceed 255 characters.',
        'office_name.required' => 'Please enter your office or company name.',
        'city.required' => 'City is required.',
        'postal_code.required' => 'Please enter your postal code.',
        'postal_code.max' => 'Postal code must be under 6 characters.',
        'image.image' => 'Uploaded file must be an image.',
        // 'image.max' => 'Image size should not exceed 512KB.',
    ];

    $validated = $request->validate($rules, $messages);

    // Update basic fields
    $customer->update($validated);

    if ($request->input('isImageDelete') == 1 && $customer->image_filename) {
        FileHelper::deleteFile(Customer::DIR_PUBLIC, $customer->image_filename);
        // $input['image_filename'] = null;
        $customer->image_filename = null;
        $customer->save();
    }

    // if ($request->hasFile('image')) {
    //     $customer->image_filename = $this->handleImageUpload($request, $request->name);
    //     $customer->save();
    // }

    if ($request->has('cropped_image') && $request->input('cropped_image')) {
        $customer->image_filename = $this->handleCroppedImageUpload($request, $request->name);
        $customer->save();
    }

    if ($request->ajax()) {
        return response()->json(['message' => 'Profile updated successfully']);
    }

    return redirect()->route('customer.profile')->with('success', 'Profile updated successfully');
}

private function handleCroppedImageUpload(Request $request, string $name): string
{
    $base64Image = $request->input('cropped_image');

    // Extract image data from base64
    $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
    $imageData = base64_decode($imageData);

    // Detect image extension
    preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches);
    $extension = $matches[1] ?? 'jpg'; // fallback to jpg

    // Generate a clean filename
    $fileName = Utility::generateFileName($name, $extension);

    $storagePath = storage_path('app/public/' . Customer::DIR_PUBLIC . '/' . $fileName);

    // Handle compression logic based on raw size
    $approxSize = strlen($imageData); // Base64 decoded size in bytes

    $manager = new ImageManager(new GdDriver());
    $image = $manager->read($imageData);

    if ($approxSize > 524288) {
        $image->scale(width: 1024)->save($storagePath, quality: 75);
    } else {
        $image->save($storagePath);
    }

    return $fileName;
}


private function handleImageUpload(Request $request, string $name): string
{
    $file = $request->file('image');
    $extension = $file->getClientOriginalExtension();
    $fileName = Utility::generateFileName($name, $extension);

    $path = storage_path('app/public/' . Customer::DIR_PUBLIC . '/' . $fileName);

    $manager = new ImageManager(new GdDriver()); // ✅ Correct way in v3

    if ($file->getSize() > 524288) {
        $manager->read($file)
            ->scale(width: 1024)
            ->save($path, quality: 75);
    } else {
        $file->storeAs(Customer::DIR_PUBLIC, $fileName);
    }

    return $fileName;
}

    public function showChangePasswordForm()
{
    return view('pages.change_password');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required'],
        'new_password' => ['required', 'min:6', 'confirmed'],
    ]);

    $customer_id = auth('customer')->id();
    $customer = Customer::findOrFail($customer_id);

    if (!Hash::check($request->current_password, $customer->password)) {
        return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
    }

    $customer->password = $request->new_password;
    $customer->save();

    return redirect()->route('customer.profile')->with('success', 'Password changed successfully.');
}

public function downloadHowToUse()
{
    $pdf = Pdf::loadView('pages.how_to_use_pdf');
    // return view('pages.how_to_use_pdf');
    return $pdf->download('Zopa_How_To_Use.pdf');
}

    public function feedbacks()
    {
        $customer = auth('customer')->user();

        $feedbacks = Feedback::where('customer_id', $customer->id)
                    ->latest()
                    ->paginate(Utility::PAGINATE_COUNT);

        return view('pages.feedbacks', compact('feedbacks'));
    }

    public function storeFeedback(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $customer = auth('customer')->user();

        Feedback::create([
            'customer_id' => $customer->id,
            'message' => $request->message,
            'is_public' => false, // You can change this to false if admin should approve first
        ]);

        return redirect()->route('feedbacks')->with('success', 'Thank you for your feedback!');
    }

public function how_to_use()
    {
        return view('pages.how_to_use');
    }
public function site_map()
    {
        return view('pages.site_map');
    }

}
