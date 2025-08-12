<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\AddonWallet;
use App\Models\CustomerMeal;
use App\Models\CustomerOrder;
use App\Models\MealWallet;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $kitchenId = auth('kitchen')->id();
        $status = request('status');

        // Build a clean base query with kitchen filter
        $baseQuery = CustomerOrder::whereHas('customer', function ($q) use ($kitchenId) {
            $q->where('kitchen_id', $kitchenId);
        });

        // Clone for counting inactive (status = 0)
        $inactiveCount = (clone $baseQuery)->where('status', Utility::ITEM_INACTIVE)->count();
        $count_new = $inactiveCount < 99 ? $inactiveCount : '99+';

        $is_active = isset($status) ? decrypt($status) : ($inactiveCount == 0 ? 1 : 0);

        // Main listing
        $customer_orders = (clone $baseQuery)
            ->where('status', $is_active)
            ->orderByDesc('id')
            ->paginate(Utility::PAGINATE_COUNT);

        // Count not-paid orders
        $count_not_paid = (clone $baseQuery)
            ->where('is_paid', Utility::ITEM_INACTIVE)
            ->count();

        // Override listing if "not paid" is selected
        if (isset($status) && decrypt($status) == Utility::STATUS_NOTPAID) {
            $is_active = Utility::STATUS_NOTPAID;

            $customer_orders = (clone $baseQuery)
                ->where('is_paid', Utility::ITEM_INACTIVE)
                ->orderByDesc('id')
                ->paginate(Utility::PAGINATE_COUNT);
        }

        return view('kitchen.customer_order.index', compact(
            'customer_orders',
            'is_active',
            'count_new',
            'count_not_paid'
        ));
    }


    public function changePayment($id)
    {
        $kitchenId = auth('kitchen')->id();
        $customer_order = CustomerOrder::findOrFail(decrypt($id));
        $requested_kitchen_id = $customer_order->customer->kitchen->id;
        if($kitchenId!=$requested_kitchen_id) {
            abort(404);
        }
        $is_paid = $customer_order->is_paid ? 0 : 1;
        $customer_order->update(['is_paid' => $is_paid]);

        return redirect()->route('kitchen.orders.index')->with(['success' => 'Status changed Successfully']);
    }

    public function activate($id, $is_paid = null)
    {
        $customer_order = CustomerOrder::findOrFail(decrypt($id));

        if ($customer_order->status == Utility::ITEM_INACTIVE) {
            $customer_order->update([
                'status' => Utility::ITEM_ACTIVE,
                'is_paid' => $is_paid == 'paid' ? Utility::ITEM_ACTIVE : Utility::ITEM_INACTIVE,
            ]);

            $this->creditToMealWallet($customer_order);
            $this->creditToAddonWallet($customer_order);
        }else {
            abort(404);
        }

        return redirect()->route('kitchen.orders.index')->with([
            'success' => 'Order activated! Wallets updated accordingly.',
        ]);
    }

    private function creditToMealWallet(CustomerOrder $order)
    {
        if ($order->meals && $order->meals->isNotEmpty()) {
            // Group meals by wallet_group_id
            $customerId = $order->customer_id;

            // Group purchased meals by wallet_group_id and sum their quantities
            $groupedQuantities = [];

            foreach ($order->meals as $customerMeal) {
                $meal = $customerMeal->meal;

                if (!$meal || !$meal->wallet_group_id) {
                    continue; // Skip if meal or wallet_group is not set
                }

                $walletGroupId = $meal->wallet_group_id;
                $quantity = $customerMeal->quantity;

                if (!isset($groupedQuantities[$walletGroupId])) {
                    $groupedQuantities[$walletGroupId] = 0;
                }

                $groupedQuantities[$walletGroupId] += $quantity;
            }

            // Update or create entries in meal_wallet
            foreach ($groupedQuantities as $walletGroupId => $quantity) {
                $wallet = MealWallet::where('customer_id', $customerId)
                    ->where('wallet_group_id', $walletGroupId)
                    ->first();

                if ($wallet) {
                    $wallet->increment('quantity', $quantity);
                } else {
                    // Check if any other wallet of the customer is already active (is_on = 1)
                    $hasActiveWallet = MealWallet::where('customer_id', $customerId)
                        ->where('is_on', 1)
                        ->exists();
                    MealWallet::create([
                        'customer_id' => $customerId,
                        'wallet_group_id' => $walletGroupId,
                        'quantity' => $quantity,
                        'status' => 1,
                        'is_on'           => $hasActiveWallet ? 0 : 1, // set is_on=1 only if none exists
                    ]);
                }
            }
        }
    }

    private function creditToAddonWallet(CustomerOrder $customer_order)
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

}
