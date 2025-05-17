<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h5 class="mb-1">Meal
                    <?php if(!$order->is_auto && $type=="daily"): ?>
                        <small><span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i> Extra ordered</span></small>
                    <?php endif; ?>
                </h5>
                <p class="mb-1 text-muted small">
                    <i class="bi bi-calendar-event"></i> <?php echo e(\Carbon\Carbon::parse($order->date)->format('d M Y (l)')); ?>

                </p>
                <p class="mb-1 text-muted small">
                    <i class="bi bi-list-ol"></i> Quantity: <strong><?php echo e($order->quantity); ?></strong>
                </p>
                <?php if($order->dailyAddons->isNotEmpty()): ?>
                <h5 class="mb-1">Addons</h5>
                    <ul class="mb-0 ps-3 small text-muted">
                        <?php $__currentLoopData = $order->dailyAddons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addonItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($addonItem->addon->name); ?> (<?php echo e($addonItem->quantity); ?>)</li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="text-end mt-2 mt-sm-0">
                <?php if($order->is_delivered): ?>
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Delivered</span>
                <?php else: ?>
                    <?php if($order->status): ?>
                        <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Scheduled</span>
                    <?php else: ?>
                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Cancelled</span>
                    <?php endif; ?>
                <?php endif; ?>

                
                <?php if(isset($meal_type) && ($meal_type=='extra_meal') && $order->status): ?>
                    <form action="<?php echo e(route('customer.daily_meals.cancel', $order->id)); ?>" method="POST" class="cancel-order mt-2">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger cancel-btn" data-order-id="<?php echo e($order->id); ?>">
                            <i class="bi bi-x-circle"></i> Cancel Order
                        </button>
                    </form>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\partials\order_card.blade.php ENDPATH**/ ?>