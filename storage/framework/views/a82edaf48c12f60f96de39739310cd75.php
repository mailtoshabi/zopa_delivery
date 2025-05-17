<?php $__env->startSection('title', 'Change Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-4">
    <h2 class="mb-4">Change Password</h2>

    <form method="POST" action="<?php echo e(route('customer.password.update')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="card mb-4">
            <div class="card-body row g-3">

                <div class="col-md-6">
                    <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                    <input id="current_password" name="current_password" type="password" class="form-control">
                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6">
                    <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                    <input id="new_password" name="new_password" type="password" class="form-control">
                    <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="col-md-6">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                    <input id="new_password_confirmation" name="new_password_confirmation" type="password" class="form-control">
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-zopa">
                <i class="fas fa-key me-1"></i> Change Password
            </button>
            <a href="<?php echo e(route('customer.profile')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Profile
            </a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\change_password.blade.php ENDPATH**/ ?>