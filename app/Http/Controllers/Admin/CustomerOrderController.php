<?php

namespace App\Http\Controllers\Admin;

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
        $status = request('status');
        $count = CustomerOrder::where('status',Utility::ITEM_INACTIVE)->count();
        $count_new = $count<99? $count:'99+';
        $is_active = isset($status)? decrypt(request('status')) : ($count==0?1:0);
        $customer_orders = CustomerOrder::orderBy('id', 'desc')->where('status',$is_active)->paginate(Utility::PAGINATE_COUNT);
        $count_not_paid = CustomerOrder::where('is_paid',Utility::ITEM_INACTIVE)->count();
        if(isset($status)&&decrypt($status)==Utility::STATUS_NOTPAID) {
            $is_active = Utility::STATUS_NOTPAID;
            $customer_orders = CustomerOrder::orderBy('id', 'desc')->where('is_paid',Utility::ITEM_INACTIVE)->paginate(Utility::PAGINATE_COUNT);
        }
        return view('admin.customer_order.index', compact('customer_orders','is_active','count_new','count_not_paid'));
    }

    public function changePayment($id)
    {
        $customer_order = CustomerOrder::findOrFail(decrypt($id));
        $is_paid = $customer_order->is_paid ? 0 : 1;
        $customer_order->update(['is_paid' => $is_paid]);

        return redirect()->route('admin.orders.index')->with(['success' => 'Status changed Successfully']);
    }

    public function activate($id)
    {
        $customer_order = CustomerOrder::findOrFail(decrypt($id));

        if ($customer_order->status == Utility::ITEM_INACTIVE) {
            // 1️⃣ Activate and mark paid
            $customer_order->update([
                'status' => Utility::ITEM_ACTIVE,
                'is_paid' => Utility::ITEM_ACTIVE,
            ]);

            // 2️⃣ Process Meals → MealWallet (only if meals exist)
            $this->processMeals($customer_order);

            // 3️⃣ Process Addons → AddonWallet (only if addons exist)
            $this->processAddons($customer_order);
        }

        return redirect()->route('admin.orders.index')->with([
            'success' => 'Order activated! Wallets updated accordingly.',
        ]);
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

}
