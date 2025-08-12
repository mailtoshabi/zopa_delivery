<?php $__env->startSection('title', 'My Cart - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            My Cart
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(empty($cart)): ?>
        <p>Your cart is empty.</p>
        <div class="mt-4">
            <p>Why not check out our <a href="<?php echo e(route('front.meal.plan')); ?>">Meal Plans</a> or <a href="<?php echo e(route('front.show.addons')); ?>">Addons</a>?</p>
        </div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php $grandTotal = 0; ?>
                <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $subtotal = $item['price'] * $item['quantity'];
                        $grandTotal += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo e(ucfirst($item['type'])); ?> - <?php echo e($item['name']); ?></td>
                        <td><?php echo e($item['quantity']); ?></td>
                        <td>₹<?php echo e(number_format($item['price'], 2)); ?></td>
                        <td>₹<?php echo e(number_format($subtotal, 2)); ?></td>
                        <td>
                            <form action="<?php echo e(route('cart.removeItem')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="type" value="<?php echo e($item['type']); ?>">
                                <input type="hidden" name="id" value="<?php echo e($item['id']); ?>">
                                <button class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <h4>Grand Total: ₹<?php echo e(number_format($grandTotal, 2)); ?></h4>

        <a href="<?php echo e(route('cart.checkout')); ?>" class="btn btn-success">Proceed to Checkout</a>
        <form action="<?php echo e(route('cart.clear')); ?>" method="POST" class="d-inline">
            <?php echo csrf_field(); ?>
            <button class="btn btn-warning">Clear Cart</button>
        </form>
        <div class="mt-4">
            <p>Continue buying with <a href="<?php echo e(route('front.meal.plan')); ?>">Meal Plans</a> or <a href="<?php echo e(route('front.show.addons')); ?>">Addons</a>?</p>
        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\cart.blade.php ENDPATH**/ ?>