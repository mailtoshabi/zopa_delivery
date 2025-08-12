<?php $__env->startSection('title', 'Daily Tiffin & Meal Delivery Service in Kerala | ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-4">
    <div class="text-center mb-4">
        <h1 class="position-relative d-inline-block px-4 py-2">
            <?php echo e(__('messages.welcome')); ?>

        </h1>
        <div class="mt-2" style="width: 200px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="row align-items-center my-5">
        <div class="col-md-6 text-center mb-4 mb-md-0">
            
            <video class="w-100 rounded shadow" autoplay muted loop playsinline poster="<?php echo e(asset('front/images/home-meal-poster.jpeg')); ?>">
                <source src="<?php echo e(asset('front/videos/home-meal.mp4')); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="col-md-6">
            <h3><?php echo e(__('messages.feature')); ?></h3>
            <p class="mt-3">
                <strong><?php echo config('app.name'); ?></strong> <?php echo e(__('messages.about_short')); ?>

            </p>
            <a href="<?php echo e(route('front.meal.plan')); ?>" class="btn btn-zopa px-4 py-2 mt-3">
                <b><?php echo e(__('messages.meal_plans.explore')); ?></b>
            </a>
        </div>
    </div>
    <?php if(app()->getLocale() === 'ml'): ?>
    <?php echo $__env->make('partials.why_choose_us_ml', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php else: ?>
    <?php echo $__env->make('partials.why_choose_us', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
</div>

<?php echo $__env->make('partials.how_to_use_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function () {
        if (!sessionStorage.getItem('howToUseShown')) {
            $('#howToUseModal').modal('show');
            sessionStorage.setItem('howToUseShown', 'true');
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\home.blade.php ENDPATH**/ ?>