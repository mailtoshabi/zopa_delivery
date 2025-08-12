<?php $__env->startSection('title', 'Payment Terms - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">Payment Terms</h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <section class="mt-4">
        <h3 data-pm-slice="1 1 []">1. Payment Methods</h3>
        <p><?php echo config('app.name'); ?> offers multiple payment options for your convenience. You can make payments using:</p>
        <ul data-spread="false">
        <li>
        <p>Credit/Debit Cards (Visa, MasterCard, RuPay, etc.)</p>
        </li>
        <li>
        <p>UPI (Google Pay, PhonePe, Paytm, etc.)</p>
        </li>
        <li>
        <p>Net Banking</p>
        </li>
        <li>
        <p>Wallet Payments</p>
        </li>
        <li>
        <p>Cash on Delivery (available for select locations)</p>
        </li>
        </ul>
        <h3>2. Billing &amp; Charges</h3>
        <ul data-spread="false">
        <li>
        <p>All meal plan subscriptions and single meal purchases must be paid in full at the time of ordering.</p>
        </li>
        <li>
        <p>Prices displayed on our website include applicable taxes unless otherwise stated.</p>
        </li>
        <li>
        <p>In case of any promotional discounts or coupon codes, the final payable amount will be reflected at checkout.</p>
        </li>
        </ul>
        <h3>3. Subscription Payments</h3>
        <ul data-spread="false">
        <li>
        <p>Subscriptions are billed in advance based on the selected plan (daily, weekly, or monthly).</p>
        </li>
        <li>
        <p>Auto-renewal may be enabled for subscription plans, ensuring uninterrupted service. You can manage or cancel renewals from your account.</p>
        </li>
        <li>
        <p>If payment fails or is incomplete, your subscription may be paused until payment is received.</p>
        </li>
        </ul>
        <h3>4. Refunds &amp; Cancellations</h3>
        <ul data-spread="false">
        <li>
        <p><strong>Cancellations:</strong> Orders can only be canceled within a specific timeframe before meal preparation begins. Cancellation policies vary by meal type and subscription plan.</p>
        </li>
        <li>
        <p><strong>Refunds:</strong> Refunds are processed only under the following conditions:</p>
        <ul data-spread="false">
        <li>
        <p>If an order is canceled within the allowed period.</p>
        </li>
        <li>
        <p>If there is an issue with the meal due to quality concerns.</p>
        </li>
        <li>
        <p>In case of duplicate payments.</p>
        </li>
        </ul>
        </li>
        <li>
        <p>Refunds will be processed within 7-10 business days via the original payment method.</p>
        </li>
        </ul>
        <h3>5. Failed Transactions</h3>
        <ul data-spread="false">
        <li>
        <p>If your payment fails, please check with your bank or payment provider before attempting another transaction.</p>
        </li>
        <li>
        <p>In case of a deducted amount but no order confirmation, contact our support team with transaction details for resolution.</p>
        </li>
        </ul>
        <h3>6. Changes to Payment Terms</h3>
        <p><?php echo config('app.name'); ?> reserves the right to modify payment terms at any time. Any changes will be updated on this page and communicated to customers where applicable.</p>
        <p>For any payment-related queries, please contact our support team at <a><strong><?php echo e(Utility::SUPPORT_MAIL); ?></strong></a>.</p>
    </section>


</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\payment_terms.blade.php ENDPATH**/ ?>