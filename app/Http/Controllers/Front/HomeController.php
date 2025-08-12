<?php

namespace App\Http\Controllers\Front;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\Addon;
use App\Models\AddonWallet;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Meal;
use App\Models\MealWallet;
use App\Models\CustomerOrder;
use App\Models\DailyAddon;
use App\Models\DailyMeal;
use App\Models\MealLeave;
use App\Models\Feedback;
use App\Models\MessCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;

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

     public function frontLang($lang)
    {
        $allowed = ['en', 'ml'];
        if (!in_array($lang, $allowed)) {
            abort(404);
        }
        if ($lang) {
             App::setLocale($lang);
            Session::put('locale', $lang);
            Session::save();
            if (Auth::guard('customer')->check()) {
                $customer_id = Auth::guard('customer')->id();
                $customer = Customer::findOrFail($customer_id);
                $customer->language = $lang;
                $customer->save();
            }
            return redirect()->back()->with('locale', $lang);
        } else {
            return redirect()->back();
        }
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

    // public function showMeals($slug)
    // {
    //     $mess_category = MessCategory::where('slug', $slug)->firstOrFail();

    //     $meals = Meal::withKitchenOverrides()
    //         ->where('meals.status', Utility::ITEM_ACTIVE)
    //         ->where('meals.mess_category_id', $mess_category->id)
    //         ->with(['ingredients', 'remarks'])
    //         ->get();

    //     return view('pages.meal_plan', compact('meals', 'mess_category'));
    // }

    public function showMeals($slug)
    {
        $mess_category = MessCategory::where('slug', $slug)->firstOrFail();

        $meals = Meal::withKitchenOverrides()
            ->where('meals.status', Utility::ITEM_ACTIVE)
            ->where('meals.mess_category_id', $mess_category->id)
            ->with(['ingredients', 'remarks'])
            ->get();

        return view('pages.meal_plan', compact('meals', 'mess_category'));
    }

    public function showMealPlans()
    {
        $meals = Meal::with(['ingredients', 'remarks'])
                ->where('status', Utility::ITEM_ACTIVE)
                ->where('category_id', 1)
                ->get();

        return view('pages.meal_plan', compact('meals'));
    }

    public function showSingleMeal()
    {
        $meal = Meal::with(['ingredients', 'remarks'])
                ->where('status', Utility::ITEM_ACTIVE)
                ->where('category_id', 2)
                ->first();

        return view('pages.single_meal', compact('meal'));
    }

    public function showMealPurchasePage($encryptedMealId)
    {
        try {
            $mealId = decrypt($encryptedMealId);

            $meal = Meal::withKitchenOverrides($mealId)
                ->with(['ingredients', 'remarks'])
                ->firstOrFail();

            if ($meal->status != Utility::ITEM_ACTIVE) {
                abort(404);
            }

            // $addons = Addon::where('status', Utility::ITEM_ACTIVE)->get();
            $addons = Addon::withKitchenOverrides()
            ->having('status', Utility::ITEM_ACTIVE)
            ->get();

            return view('pages.meal_purchase', compact('meal','addons'));
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    public function purchaseMeal(Request $request, $meal_id)
    {
        $invoiceNo = $this->generateInvoiceNumber();
        $mealId = decrypt($meal_id);

        $meal = Meal::withKitchenOverrides($mealId)->firstOrFail();

        if ($meal->status != Utility::ITEM_ACTIVE) {
            abort(404);
        }

        $validated = $request->validate([
            'addons' => 'nullable|array',
            'addons.*.quantity' => 'nullable|integer|min:1',
            'pay_method' => 'required|in:1,2',
        ]);

        $customer = Auth::guard('customer')->user();
        $payMethod = $validated['pay_method'];

        $customerOrder = CustomerOrder::create([
            'invoice_no' => $invoiceNo,
            'customer_id' => $customer->id,
            'pay_method' => $payMethod,
            'discount' => 0,
            'delivery_charge' => 0,
            'amount' => 0,
            'ip_address' => $request->ip(),
            'is_paid' => Utility::ITEM_INACTIVE,
            'status' => Utility::ITEM_INACTIVE,
        ]);

        $customerOrder->meals()->create([
            'meal_id' => $meal->id,
            'price' => $meal->price,
            'quantity' => $meal->quantity,
        ]);

        if (!empty($validated['addons'])) {
            $addonIds = array_keys($validated['addons']);
            // $addons = Addon::whereIn('id', $addonIds)->get()->keyBy('id');
            $addons = Addon::withKitchenOverrides()
            ->whereIn('addons.id', $addonIds)
            ->having('status', '=', Utility::ITEM_ACTIVE)
            ->get()
            ->keyBy('id');

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
        $customerOrder->update(['amount' => $grandTotal]);

        if ($payMethod == Utility::PAYMENT_ONLINE) {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $razorpayOrder = $api->order->create([
                'receipt' => $invoiceNo,
                'amount' => $grandTotal * 100,
                'currency' => 'INR',
                'payment_capture' => 1,
                'notes' => [
                    'invoice_no' => $customerOrder->invoice_no,
                    'customer_name' => $customerOrder->customer->name,
                ]
            ]);

            $customerOrder->update([
                'razorpay_order_id' => $razorpayOrder['id']
            ]);

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
        $addons = Addon::withKitchenOverrides()
            // ->having('status', '=', Utility::ITEM_ACTIVE) // ✅ Fix ambiguity
            ->where('addons.status', Utility::ITEM_ACTIVE)
            ->get();

        return view('pages.buy_addons', compact('addons'));
    }


    public function showAddonPurchasePage(Request $request)
    {
        $validated = $request->validate([
            'addons' => 'required|array',
        ], [
            'addons.required' => 'Click on the addons to select',
            'addons.*.quantity.min' => 'Quantity must be at least 1.',
        ]);

        $addonIds = array_keys(array_filter($validated['addons'], fn($a) => !empty($a['quantity'])));

        if (empty($addonIds)) {
            return redirect()->back()->withErrors(['Please select at least one addon.']);
        }

        $addons = Addon::withKitchenOverrides()
            ->whereIn('addons.id', $addonIds)
            ->having('status', '=', Utility::ITEM_ACTIVE) // ✅ Fix ambiguity
            ->get()
            ->map(function ($addon) use ($validated) {
                $addon->selected_quantity = $validated['addons'][$addon->id]['quantity'];
                return $addon;
            });

        return view('pages.addon_purchase', compact('addons'));
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

        $addonIds = array_keys($validated['addons']);

        // ✅ Kitchen-specific overrides + active filter
        $addons = Addon::withKitchenOverrides()
            ->whereIn('addons.id', $addonIds)
            ->having('status', '=', Utility::ITEM_ACTIVE)
            ->get()
            ->keyBy('id');

        if ($addons->isEmpty()) {
            return redirect()->route('addons.buy')->withErrors(['Invalid addon selection.']);
        }

        $customer = Auth::guard('customer')->user();
        $payMethod = $validated['pay_method'];

        $customerOrder = CustomerOrder::create([
            'invoice_no' => $invoiceNo,
            'customer_id' => $customer->id,
            'pay_method' => $payMethod,
            'discount' => 0,
            'delivery_charge' => 0,
            'amount' => 0,
            'ip_address' => $request->ip(),
            'is_paid' => Utility::ITEM_INACTIVE,
            'status' => Utility::ITEM_INACTIVE,
        ]);

        foreach ($validated['addons'] as $addonId => $addonData) {
            if (!isset($addons[$addonId])) continue;

            $customerOrder->addons()->create([
                'addon_id' => $addonId,
                'quantity' => $addonData['quantity'],
                'price' => $addons[$addonId]->price, // ✅ kitchen override price
            ]);
        }

        $grandTotal = $customerOrder->calculateTotal();
        $customerOrder->update(['amount' => $grandTotal]);

        if ($payMethod == Utility::PAYMENT_ONLINE) {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $razorpayOrder = $api->order->create([
                'receipt' => $invoiceNo,
                'amount' => $grandTotal * 100,
                'currency' => 'INR',
                'payment_capture' => 1,
                'notes' => [
                    'invoice_no' => $customerOrder->invoice_no,
                    'customer_name' => $customerOrder->customer->name,
                ]
            ]);

            $customerOrder->update([
                'razorpay_order_id' => $razorpayOrder['id']
            ]);

            Session::put('razorpay_order_id', $razorpayOrder['id']);
            Session::put('customer_order_id', $customerOrder->id);

            return view('pages.razorpay_payment', [
                'order' => $razorpayOrder,
                'customer' => $customer,
                'razorpayKey' => config('services.razorpay.key'),
                'grandTotal' => $grandTotal,
            ]);
        }

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

    public function payLater($id) {
        $orderId = decrypt($id);
        $order = CustomerOrder::findOrFail($orderId);
        // if ($payMethod == Utility::PAYMENT_ONLINE) {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $razorpayOrder = $api->order->create([
                'receipt' => $order->invoice_no,
                'amount' => ($order->amount) * 100,
                'currency' => 'INR',
                'payment_capture' => 1,
                'notes' => [
                    'invoice_no' => $order->invoice_no,
                    'customer_name' => $order->customer->name,
                ]
            ]);

            $order->update([
                'razorpay_order_id' => $razorpayOrder['id']
            ]);

            Session::put('razorpay_order_id', $razorpayOrder['id']);
            Session::put('customer_order_id', $order->id);

            return view('pages.razorpay_payment', [
                'order' => $razorpayOrder,
                'customer' => $order->customer,
                'razorpayKey' => config('services.razorpay.key'),
                'grandTotal' => $order->amount,
            ]);
        // }
    }

    public function myWallet()
    {
        $customerId = Auth::guard('customer')->id();

        // Meal Wallet
        $meal_wallets = MealWallet::where('customer_id', $customerId)->get();

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

        // Meals processing today
        // $mealsProcessingToday = DailyMeal::where('customer_id', $customerId)
        //     ->whereDate('created_at', $today)
        //     ->where('status', 1)
        //     ->where('is_delivered', 0)
        //     ->sum('quantity');

        // Addons processing today
        // $addonsProcessingToday = DailyAddon::whereHas('dailyMeal', function ($query) use ($customerId, $today) {
        //     $query->where('customer_id', $customerId)
        //         ->whereDate('created_at', $today)
        //         ->where('status', 1);
        // })->sum('quantity');

        return view('pages.my_wallet', compact('meal_wallets', 'addon_wallet', 'monthlyLeaveCount', 'maxLeaves','activeLeaveCount','maxActiveLeaves'));
    }

    public function makeDefault(Request $request)
    {
        $customer = auth()->guard('customer')->user(); // or however you're authenticating
        $walletId = $request->input('wallet_id');

        // Safety: Ensure the wallet belongs to this customer
        $wallet = MealWallet::where('id', $walletId)
                            ->where('customer_id', $customer->id)
                            ->first();

        if (!$wallet) {
            return response()->json(['success' => false, 'message' => 'Invalid wallet.']);
        }

        if ($wallet->quantity==0) {
            return response()->json(['success' => false, 'message' => 'Wallet is Empty.']);
        }

        if (!$wallet->status) {
            return response()->json(['success' => false, 'message' => 'Wallet is Inactive.']);
        }

        // Reset all others to not default
        MealWallet::where('customer_id', $customer->id)
                ->update(['is_on' => 0]);

        // Set the selected one to default
        $wallet->is_on = 1;
        $wallet->save();

        return response()->json(['success' => true, 'message' => 'Default wallet updated.']);
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

        $meal_wallets = MealWallet::where('customer_id', $customerId)->where('status',Utility::ITEM_ACTIVE)->get();
        $default_wallet = MealWallet::where('customer_id', $customerId)->where('is_on',Utility::ITEM_ACTIVE)->first();
        $mealsLeft = $default_wallet ? $default_wallet->quantity : 0;

        return view('pages.daily_meals', [
            'type' => 'daily',
            'todayOrders' => $todayOrders,
            'cutoffTime' => $cutoffTime,
            'meal_wallets' => $meal_wallets,
            'mealsLeft' => $mealsLeft,
            'default_wallet' => $default_wallet,
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
            $message = 'Cancellation is only allowed before ' . FileHelper::convertTo12Hour($customer->cutoff_time);

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
                    'message' => 'Extra meal requests for today are only allowed before ' . FileHelper::convertTo12Hour($customer->cutoff_time)
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

    $date = Carbon::createFromFormat('d-m-Y', $request->date)->startOfDay();
    $now = now();
    $today = $now->copy()->startOfDay();
    $cutoffTime = now()->setTime($this->cutoffHour, $this->cutoffMinute);

    $customer = auth('customer')->user();

    // ✅ Per-month leave limit
    $leaveMonthStart = $date->copy()->startOfMonth();
    $leaveMonthEnd = $date->copy()->endOfMonth();

    if ($date->isToday() && $now->gt($cutoffTime)) {
        return $request->ajax()
            ? response()->json(['success' => false, 'message' => 'Cannot mark leave for today after cutoff time.'])
            : back()->with('error', 'Cannot mark leave for today after cutoff time.');
    }

    if ($date->isPast()) {
        return $request->ajax()
            ? response()->json(['success' => false, 'message' => 'Cannot mark Leave for past dates.'])
            : back()->with('error', 'Cannot mark Leave for past dates.');
    }

    if ($date->isSunday()) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Sundays are not allowed.'])
                : back()->with('error', 'Sundays are not allowed.');
        }

    $monthlyLeaveCount = MealLeave::where('customer_id', $customer->id)
        ->whereBetween('leave_at', [$leaveMonthStart, $leaveMonthEnd])
        ->count();

    if ($monthlyLeaveCount >= Utility::MAX_MONTHLY_LEAVES) {
        // return redirect()->back()->with('error', 'You can only mark up to ' . Utility::MAX_MONTHLY_LEAVES . ' leaves in ' . $date->format('F') . '.');

        return $request->ajax()
            ? response()->json(['success' => false, 'message' => 'You can only mark up to ' . Utility::MAX_MONTHLY_LEAVES . ' leaves in ' . $date->format('F') . '.'])
            : redirect()->back()->with('error', 'You can only mark up to ' . Utility::MAX_MONTHLY_LEAVES . ' leaves in ' . $date->format('F') . '.');
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
        // return redirect()->back()->with('error', 'You can only have up to ' . Utility::MAX_ACTIVE_LEAVES . ' active leaves at a time.');

        return $request->ajax()
            ? response()->json(['success' => false, 'message' => 'You can only have up to ' . Utility::MAX_ACTIVE_LEAVES . ' active leaves at a time.'])
            : redirect()->back()->with('error', 'You can only have up to ' . Utility::MAX_ACTIVE_LEAVES . ' active leaves at a time.');
    }

    // ✅ Prevent duplicate leave
    $alreadyExists = MealLeave::where('customer_id', $customer->id)
        ->whereDate('leave_at', $date)
        ->exists();

    if ($alreadyExists) {
        // return redirect()->back()->with('error', 'You have already marked leave for this date.');

        return $request->ajax()
            ? response()->json(['success' => false, 'message' => 'You have already marked leave for this date.'])
            : redirect()->back()->with('error', 'You have already marked leave for this date.');
    }

    MealLeave::create([
        'customer_id' => $customer->id,
        'leave_at' => $date,
    ]);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
        ]);
    }
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
            return redirect()->back()->with('error', 'Cannot cancel today\'s leave after ' . FileHelper::convertTo12Hour($customer->cutoff_time));
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
        if(app()->getLocale() === 'ml') {
            return view('pages.about_us_ml');
        }else {
            return view('pages.about_us');
        }
    }
public function payment_terms()
    {
        if(app()->getLocale() === 'ml') {
            return view('pages.payment_terms_ml');
        }else {
            return view('pages.payment_terms');
        }
    }
public function privacy_policy()
    {
        if(app()->getLocale() === 'ml') {
            return view('pages.privacy_policy_ml');
        }else {
            return view('pages.privacy_policy');
        }
    }
public function support()
    {
        if(app()->getLocale() === 'ml') {
            return view('pages.support_ml');
        }else {
            return view('pages.support');
        }
    }
public function faq()
    {
        if(app()->getLocale() === 'ml') {
            return view('pages.faq_ml');
        }else {
            return view('pages.faq');
        }
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
        // 'postal_code' => 'required|string|max:6',
        'image' => 'nullable',
        'language' => 'required',
        'latitude'         => 'required',
        'longitude'         => 'required',
        'location_name'         => 'required',
        // 'state_id' => 'required|exists:states,id',
        // 'district_id' => 'required|exists:districts,id',
    ];

    // Custom messages
    $messages = [
        'name.required' => 'Please enter your full name.',
        'name.max' => 'Name should not exceed 255 characters.',
        'office_name.required' => 'Please enter your office or company name.',
        'city.required' => 'City is required.',
        // 'postal_code.required' => 'Please enter your postal code.',
        'postal_code.max' => 'Postal code must be under 6 characters.',
        'image.image' => 'Uploaded file must be an image.',
        // 'image.max' => 'Image size should not exceed 512KB.',
        'language.required' => 'Language is required.',
        'location_name.required'         => 'Location is required',
    ];

    $validated = $request->validate($rules, $messages);
    $postal_code = get_postal_code($request->latitude, $request->longitude);
    $validated['postal_code'] = $postal_code;
    $validated['kitchen_id'] = decrypt($request->kitchen_id);
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
        if(app()->getLocale() === 'ml') {
            return view('pages.how_to_use_ml');
        }else {
            return view('pages.how_to_use');
        }
    }
public function site_map()
    {
        if(app()->getLocale() === 'ml') {
            return view('pages.site_map_ml');
        }else {
            return view('pages.site_map');
        }
    }

}
