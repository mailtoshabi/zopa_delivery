<?php $__env->startSection('title', 'Access Denied - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">

    
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            Access Denied
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-lock fa-4x text-danger"></i>
                    </div>
                    <h4 class="mb-3">You must be logged in to access this page</h4>
                    <p class="text-muted mb-4">
                        Please log in with your account to continue, or return to our homepage.
                    </p>

                    
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                        <a href="<?php echo e(url('/')); ?>" class="btn btn-outline-primary btn-lg px-4">
                            <i class="fas fa-home me-2"></i> Home
                        </a>
                        <a href="<?php echo e(route('customer.login')); ?>" class="btn btn-success btn-lg px-4">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </a>
                    </div>
                </div>
            </div>

            
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-uppercase fw-bold mb-3">Quick Links</h6>
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="<?php echo e(route('about_us')); ?>"><?php echo e(__('messages.menu.about')); ?></a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('how_to_use')); ?>"><?php echo e(__('messages.menu.how_to_use')); ?></a></li>
                        
                        <li class="list-inline-item"><a href="<?php echo e(route('payment_terms')); ?>"><?php echo e(__('messages.menu.payment_terms')); ?></a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('privacy_policy')); ?>"><?php echo e(__('messages.menu.privacy')); ?></a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('support')); ?>"><?php echo e(__('messages.menu.support')); ?></a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('faq')); ?>"><?php echo e(__('messages.menu.faq')); ?></a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('site_map')); ?>"><?php echo e(__('messages.menu.site_map')); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\unauthenticated.blade.php ENDPATH**/ ?>