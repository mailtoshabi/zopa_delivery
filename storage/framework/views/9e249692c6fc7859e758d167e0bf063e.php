<?php $__env->startSection('title', 'Welcome to Zopa Food Drop'); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-4">
    <div class="text-center mb-4">
        <h1 class="position-relative d-inline-block px-4 py-2">
            Welcome to Zopa Food Drop
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
            <h3>Nutritious. Affordable. Delivered Daily.</h3>
            <p class="mt-3">
                Zopa Food Drop is your reliable daily meal partner. We serve healthy home-style meals to your doorstep. Whether you're a student, a working professional, or a senior, our meal plans are designed to suit your lifestyle.
            </p>
            <a href="<?php echo e(route('front.meal.plan')); ?>" class="btn btn-zopa px-4 py-2 mt-3">
                <b>Explore Meal Plans</b>
            </a>
        </div>
    </div>

    <div class="text-center mt-5">
        <h4 class="mb-3">Why Choose Zopa Food Drop?</h4>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-utensils fa-2x mb-3 text-secondary"></i>
                        <h5 class="card-title">Delicious Meals</h5>
                        <p class="card-text">Crafted with care by our expert chefs.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-bowl-food fa-2x mb-3 text-primary"></i>
                        <h5 class="card-title">Fresh Ingredients</h5>
                        <p class="card-text">We use fresh and locally sourced ingredients to prepare your meals daily.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-truck-fast fa-2x mb-3 text-success"></i>
                        <h5 class="card-title">Timely Delivery</h5>
                        <p class="card-text">We make sure your meals reach you on time every day — hot and tasty!</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-wallet fa-2x mb-3 text-warning"></i>
                        <h5 class="card-title">Flexible Plans</h5>
                        <p class="card-text">Choose from daily, weekly or monthly plans that fit your budget and schedule.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
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