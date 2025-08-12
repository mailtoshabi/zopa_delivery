<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $comingOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-sm-12 mb-3">
        <div class="card shadow <?php echo e($order->status == 0 ? 'border-danger' : ''); ?>">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <p><small><?php echo e($order->date->format('d M Y')); ?></small></p>
                    <p class="mb-0 fw-bold">
                        <?php echo e($order->quantity); ?> Meal<?php echo e($order->quantity > 1 ? 's' : ''); ?>

                        <?php if($order->status == 0): ?>
                            <span class="badge bg-danger ms-2">Cancelled</span>
                        <?php endif; ?>
                    </p>

                    <?php if($order->dailyAddons->isNotEmpty()): ?>
                        <ul class="mb-0 ps-3 small text-muted">
                            <?php $__currentLoopData = $order->dailyAddons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addonItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($addonItem->addon->name); ?> (<?php echo e($addonItem->quantity); ?>)</li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <?php if($order->status == 1 && now()->format('H:i') < $lastOrderTime): ?>
                    <form method="POST" action="<?php echo e(route('customer.daily_meals.cancel', $order->id)); ?>" class="cancel-order">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Cancel before <?php echo e($lastOrderTime); ?>">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </form>
                <?php elseif($order->status == 1): ?>
                    <span class="text-muted" data-bs-toggle="tooltip" title="Cancellation time passed">
                        <i class="fa-solid fa-clock"></i>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-sm-12">
            <div class="alert alert-info">No upcoming orders scheduled.</div>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\partials\coming_orders.blade.php ENDPATH**/ ?>