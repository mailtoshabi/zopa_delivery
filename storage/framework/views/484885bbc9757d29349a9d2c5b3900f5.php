<?php $__env->startSection('title', 'How to Use Zopa - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-4">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            How to Use <?php echo config('app.name'); ?>
            <?php echo e(app()->getLocale()); ?>

        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #27ae60; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="row">
        <?php echo $__env->make('partials.how_to_use_content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\how_to_use.blade.php ENDPATH**/ ?>