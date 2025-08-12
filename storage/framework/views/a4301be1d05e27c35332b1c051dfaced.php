<?php $__env->startSection('content'); ?>
<div class="container text-center">
    <h2 class="text-danger">Payment Failed</h2>
    <p><?php echo e(session('error') ?? 'There was a problem processing your payment.'); ?></p>
    <a href="<?php echo e(route('front.meal.plan')); ?>" class="btn btn-primary">Go Back Meal Plans</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\payment_failed.blade.php ENDPATH**/ ?>