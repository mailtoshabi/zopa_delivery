<?php $__env->startSection('title', 'Payment Information - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">Payment Information</h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <p class="text-success"><strong>Thank you!</strong> Your Order has been placed.</p>

                    <?php if($payment_method == Utility::PAYMENT_ONLINE): ?>
                        <h4 class="mb-3 text-primary">Online Payment Details</h4>
                        <div class="alert alert-success">
                            Payment successful. Your order has been activated and credited to your <a href="<?php echo e(route('my.wallet')); ?>"> Wallet</a>.
                        </div>
                    <?php elseif($payment_method == Utility::PAYMENT_BNK): ?>
                        <h4 class="mb-3 text-success">Online Payment Details</h4>

                        <p><strong>Bank Name:</strong> Zopa Bank</p>
                        <p><strong>Account Number:</strong> 1234567890</p>
                        <p><strong>IFSC:</strong> ZOPA0001234</p>
                        <p><strong>UPI ID:</strong> zopa@upi</p>

                        <div class="text-center my-4">
                            <img src="<?php echo e(asset('front/images/payment_qr.png')); ?>" alt="Payment QR" style="max-width: 300px;" class="img-fluid rounded shadow">
                            <p class="text-muted mt-2">Scan to Pay via UPI</p>
                        </div>

                        <div class="alert alert-info">
                            <p>Your meal plan subscription is successful.</p>
                            <p>After making the payment, please inform our delivery person or <a href="<?php echo e(route('support')); ?>">contact support</a> with your payment reference.</p>
                            <p>Once approved by the admin, your meals and addons will be credited to your <a href="<?php echo e(route('my.wallet')); ?>">wallet</a>.</p>

                        </div>

                    <?php elseif($payment_method == Utility::PAYMENT_COD): ?>
                        <h4 class="mb-3 text-primary">Cash on Delivery</h4>
                        <div class="alert alert-warning">
                            <p>Your meal plan subscription is successful.</p>
                            <p>Please keep the payment ready. Our delivery person will collect it upon delivering your meal or <a href="<?php echo e(route('support')); ?>">contact support</a> with your payment reference.</p>
                            <p>Once approved by the admin, your meals and addons will be credited to your <a href="<?php echo e(route('my.wallet')); ?>">wallet</a>.</p>
                        </div>

                    <?php else: ?>
                        <div class="alert alert-danger">
                        Invalid payment method selected. Please contact <a href="<?php echo e(route('support')); ?>">Support</a>.
                        </div>
                    <?php endif; ?>

                    <?php if($payment_method == Utility::PAYMENT_ONLINE || $payment_method == Utility::PAYMENT_COD || $payment_method == Utility::PAYMENT_BNK): ?>
                        <hr>
                        <h5 class="mb-3">Order Summary</h5>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Invoice No:</strong> <?php echo e($customerOrder->invoice_no); ?></li>

                            <?php if($meals->isNotEmpty()): ?>
                                <?php $__currentLoopData = $meals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mealPivot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // $quantity = $mealPivot->quantity;
                                        $name = $mealPivot->meal->name;
                                        $price = $mealPivot->price;
                                        // $subtotal = $price * $quantity;
                                        $subtotal = $price;
                                    ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo e($name); ?> x 1
                                        <span>₹<?php echo e(number_format($subtotal, 2)); ?></span>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                            <?php if($addons->isNotEmpty()): ?>
                                <?php $__currentLoopData = $addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addonsPivot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $subtotal = $addonsPivot->price * $addonsPivot->quantity;
                                    ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo e($addonsPivot->addon->name); ?> x <?php echo e($addonsPivot->quantity); ?>

                                        <span>₹<?php echo e(number_format($subtotal, 2)); ?></span>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                            <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                Grand Total
                                <span>₹<?php echo e(number_format($customerOrder->amount, 2)); ?></span>
                            </li>
                        </ul>
                    <?php endif; ?>

                    <a href="<?php echo e(route('my.wallet')); ?>" class="btn btn-zopa mt-4 w-100">Back to My Wallet</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\payment_success.blade.php ENDPATH**/ ?>