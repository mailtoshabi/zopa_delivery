<?php

namespace App\Http\Controllers;

use App\Http\Utilities\Utility;
use App\Models\AddonWallet;
use App\Models\CustomerOrder;
use App\Models\MealWallet;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function verifyRazorpayPayment(Request $request)
    {
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpaySignature = $request->input('razorpay_signature');

        $orderId = Session::get('customer_order_id');
        $customerOrder = CustomerOrder::findOrFail($orderId);

        // Verify the signature
        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $attributes = [
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature
            ];

            $api->utility->verifyPaymentSignature($attributes); // throws SignatureVerificationError if invalid

            // Update order with payment details
            $customerOrder->update([
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_signature' => $razorpaySignature,
                'amount' => $customerOrder->amount,
                'is_paid' => Utility::ITEM_ACTIVE,
                'status' => Utility::ITEM_ACTIVE,
                'ip_address' => $request->ip(),
            ]);

            $this->processMeals($customerOrder);
            $this->processAddons($customerOrder);

            Session::forget(['customer_order_id', 'razorpay_order_id']);

            return redirect()->route('meal.payment.success', encrypt($customerOrder->id))
                ->with('success', 'Payment successful and order processed.');

        } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
            Log::error("Razorpay Signature Verification Failed: " . $e->getMessage());
            return redirect()->route('meal.payment.failed')->with('error', 'Payment verification failed.');
        }
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

