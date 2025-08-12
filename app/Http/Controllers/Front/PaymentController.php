<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
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

            $this->creditToMealWallet($customerOrder);
            $this->creditToAddonWallet($customerOrder);

            Session::forget(['customer_order_id', 'razorpay_order_id']);

            return redirect()->route('meal.payment.success', encrypt($customerOrder->id))
                ->with('success', 'Payment successful and order processed.');

        } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
            Log::error("Razorpay Signature Verification Failed: " . $e->getMessage());
            return redirect()->route('meal.payment.failed')->with('error', 'Payment verification failed.');
        }
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

