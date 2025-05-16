<?php

namespace App\Http\Controllers\Front;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\Addon;
use App\Models\AddonWallet;
use App\Models\Customer;
use App\Models\CustomerAddon;
use App\Models\Meal;
use App\Models\MealWallet;
use App\Models\CustomerMeal;
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

    public function mealPlan()
    {
        $meals = Meal::with(['ingredients', 'remarks'])
                ->where('status', Utility::ITEM_ACTIVE)
                ->where('id', '!=', Utility::SINGLE_MEAL_ID)
                ->get();

        return view('pages.meal_plan', compact('meals'));
    }

    public function singleMeal()
    {
        $meal = Meal::with(['ingredients', 'remarks'])
                ->where('status', Utility::ITEM_ACTIVE)
                ->where('id', Utility::SINGLE_MEAL_ID)
                ->first();

        return view('pages.single_meal', compact('meal'));
    }

    public function showPurchasePage($encryptedMealId)
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

    public function addons()
    {
        $addons = Addon::where('status', Utility::ITEM_ACTIVE)->get();

        return view('pages.buy_addons', compact('addons'));
    }

    public function purchaseAddon(Request $request)
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

    public function storeAddonPurchase(Request $request)
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

        if ($payMethod == Utility::PAYMENT_ONLINE) {
            // 1️⃣ Activate and mark paid
            $customerOrder->update([
                'status' => Utility::ITEM_ACTIVE,
                'is_paid' => Utility::ITEM_ACTIVE,
            ]);

            // 3️⃣ Process Addons → AddonWallet (only if addons exist)
            $this->processAddons($customerOrder);
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

    public function purchaseMeal(Request $request, $meal_id)
    {
        $invoiceNo = $this->generateInvoiceNumber();
        $meal = Meal::findOrFail(decrypt($meal_id));

        // Validate the incoming request data
        $validated = $request->validate([
            'addons' => 'nullable|array',
            'addons.*.quantity' => 'nullable|integer|min:1',
            'pay_method' => 'required|in:1,2',  // 1 = Online, 2 = Cash on Delivery
        ]);

        $customer = Auth::guard('customer')->user();
        $payMethod = $validated['pay_method'];

        // Step 1. Create CustomerOrder (the parent order)
        $customerOrder = CustomerOrder::create([
            'invoice_no' => $invoiceNo,
            'customer_id' => $customer->id,
            'pay_method' => $payMethod,
            'discount' => 0,              // No discount for single meal (adjust if needed)
            'delivery_charge' => 0,       // No delivery charge (adjust if needed)
            'is_paid' => 0,               // Not paid yet
            'status' => 0,                // Active
        ]);

        // return $customerOrder;

        // Step 2. Attach the meal into customer_meal (under the created order)
        $customerOrder->meals()->create([
            'meal_id' => $meal->id,
            'price' => $meal->price,      // Current meal price
            'quantity' => $meal->quantity, // Quantity from meal table (adjust if user chooses quantity)
        ]);


        // (Optional) Step 3: Update meal_wallet if you want to auto credit meals
        // Commented, but safe to enable later as per your logic.
        // $meal_wallet = MealWallet::firstOrNew(['customer_id' => $customer->id]);
        // $meal_wallet->quantity += $meal->quantity;
        // $meal_wallet->status = Utility::ITEM_ACTIVE;
        // $meal_wallet->save();

        if($request->addons) {
        $addonIds = array_keys($validated['addons']);
        $addons = Addon::whereIn('id', $addonIds)->get()->keyBy('id');
        if ($addons->isNotEmpty()) {
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
        }
    }

    if ($payMethod == Utility::PAYMENT_ONLINE) {
        // 1️⃣ Activate and mark paid
        $customerOrder->update([
            'status' => Utility::ITEM_ACTIVE,
            'is_paid' => Utility::ITEM_ACTIVE,
        ]);

        // 2️⃣ Process Meals → MealWallet (only if meals exist)
        $this->processMeals($customerOrder);

        // 3️⃣ Process Addons → AddonWallet (only if addons exist)
        $this->processAddons($customerOrder);
    }

        return redirect()->route('meal.payment.success', encrypt($customerOrder->id));
    }

    private function processMeals(CustomerOrder $customer_order)
    {
        if ($customer_order->meals && $customer_order->meals->isNotEmpty()) {
            // Directly sum the quantity column of CustomerMeal models
            $total_meal_quantity = $customer_order->meals->sum('quantity');

            if ($total_meal_quantity > 0) {
                $meal_wallet = MealWallet::firstOrNew(['customer_id' => $customer_order->customer_id]);

                $meal_wallet->quantity = ($meal_wallet->quantity ?? 0) + $total_meal_quantity;
                $meal_wallet->status = Utility::ITEM_ACTIVE;
                $meal_wallet->save();
            }
        }
    }

    private function processAddons(CustomerOrder $customer_order)
    {
        if ($customer_order->addons && $customer_order->addons->isNotEmpty()) {
            foreach ($customer_order->addons as $addonItem) {
                $addon_id = $addonItem->addon_id;
                $quantity = $addonItem->quantity;

                if ($quantity > 0) {
                    $addon_wallet = AddonWallet::firstOrNew([
                        'customer_id' => $customer_order->customer_id,
                        'addon_id' => $addon_id,
                    ]);

                    // Simplified wallet quantity update
                    $addon_wallet->quantity = ($addon_wallet->quantity ?? 0) + $quantity;
                    $addon_wallet->status = Utility::ITEM_ACTIVE;
                    $addon_wallet->save();
                }
            }
        }
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
                        ->where('status', Utility::ITEM_ACTIVE)
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

        return view('pages.my_wallet', compact('mealsLeft', 'addon_wallet', 'monthlyLeaveCount', 'maxLeaves','activeLeaveCount','maxActiveLeaves'));
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

    $customer_id = auth('customer')->id();
    $customer = Customer::findOrFail($customer_id);

    // Validation rules (phone and password removed)
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'office_name' => 'required|string|max:255',
        'designation' => 'nullable|string|max:255',
        'whatsapp' => 'nullable|string|max:15',
        'city' => 'required|string|max:255',
        'landmark' => 'nullable|string|max:255',
        'postal_code' => 'required|string|max:10',
        // 'state_id' => 'required|exists:states,id',
        // 'district_id' => 'required|exists:districts,id',
        'image' => 'nullable|image|max:2048',
    ]);

    // Update customer profile data
    $customer->update([
        'name' => $validated['name'],
        'office_name' => $validated['office_name'],
        'designation' => $validated['designation'],
        'whatsapp' => $validated['whatsapp'],
        'city' => $validated['city'],
        'landmark' => $validated['landmark'],
        'postal_code' => $validated['postal_code'],
        // 'state_id' => $validated['state_id'],
        // 'district_id' => $validated['district_id'],
    ]);

    if ($request->isImageDelete == 1 && $customer->image_filename) {
        FileHelper::deleteFile(Customer::DIR_PUBLIC, $customer->image_filename);
        // $input['image_filename'] = null;
        $customer->image_filename = null;
        $customer->save();
    }

    if ($request->hasFile('image')) {
        $customer->image_filename = $this->handleImageUpload($request, $request->name);
        $customer->save();
    }

    return redirect()->route('customer.profile')->with('success', 'Profile updated successfully');
}

private function handleImageUpload(Request $request, string $name): string
    {
        $fileName = Utility::generateFileName($name, $request->file('image')->extension());
        $request->image->storeAs(Customer::DIR_PUBLIC, $fileName);
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
