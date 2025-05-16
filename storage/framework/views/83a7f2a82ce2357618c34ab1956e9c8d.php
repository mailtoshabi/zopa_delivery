<div id="previous-orders-list">
    <?php $__currentLoopData = $previousOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('partials._order_row', ['order' => $order], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php if($previousOrders->hasMorePages()): ?>
    <div class="text-center mt-3">
        <button class="btn btn-outline-primary" id="load-more" data-next-page="<?php echo e($previousOrders->currentPage() + 1); ?>">
            Load More
        </button>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\partials\previous_orders.blade.php ENDPATH**/ ?>