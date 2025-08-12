<?php $__env->startSection('title', 'Cart Checkout - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">Cart Checkout</h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <?php if(empty($cart) || count($cart) === 0): ?>
        <p class="text-center">Your cart is empty.</p>
    <?php else: ?>
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3">
                <div class="card shadow">
                    <div class="card-body">

                        <h5 class="mb-3">Items in Your Cart:</h5>
                        <ul class="list-group mb-3">
                            <?php $grandTotal = 0; ?>
                            <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $grandTotal += $subtotal;
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo e(ucfirst($item['type'])); ?> - <?php echo e($item['name']); ?></strong><br>
                                        <small class="text-muted">Qty: <?php echo e($item['quantity']); ?> × ₹<?php echo e(number_format($item['price'], 2)); ?></small>
                                    </div>
                                    <span class="fw-bold">₹<?php echo e(number_format($subtotal, 2)); ?></span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>

                        <h4 class="text-end mb-4">Grand Total: ₹<?php echo e(number_format($grandTotal, 2)); ?></h4>

                        <form method="POST" action="<?php echo e(route('cart.checkout.store')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label for="pay_method" class="form-label">Select Payment Method</label>
                                <select name="pay_method" id="pay_method" class="form-select <?php $__errorArgs = ['pay_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">-- Choose Payment Option --</option>
                                    <option value="<?php echo e(Utility::PAYMENT_ONLINE); ?>">Online Payment</option>
                                    <option value="<?php echo e(Utility::PAYMENT_COD); ?>">Cash on Delivery</option>
                                </select>
                                <?php $__errorArgs = ['pay_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <button type="submit" class="btn btn-zopa w-100">
                                Confirm and Pay
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\cart_checkout.blade.php ENDPATH**/ ?>