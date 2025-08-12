<div class="card mb-2">
    <div class="card-body">
        
        <p class="mb-0 fw-bold">
            <?php if($order->status == 0): ?>
            <span class="badge bg-danger ms-2">Cancelled</span>
            <?php endif; ?>
        </p>
        <p class="card-text">Ordered on: <?php echo e($order->date->format('d M Y')); ?></p>
        <p class="card-text">Quantity: <?php echo e($order->quantity); ?></p>
        <?php if($order->dailyAddons->isNotEmpty()): ?>
            <ul class="mb-0 ps-3 small text-muted">
                <?php $__currentLoopData = $order->dailyAddons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addonItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($addonItem->addon->name); ?> (<?php echo e($addonItem->quantity); ?>)</li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\partials\_order_row.blade.php ENDPATH**/ ?>