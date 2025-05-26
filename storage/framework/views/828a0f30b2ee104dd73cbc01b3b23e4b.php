<?php $__env->startSection('title', 'Feedbacks - Zopa Food Drop'); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-2">
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            Feedbacks
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>
    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="card shadow">
                <div class="card-header">
                    <h5>Submit Your Feedback</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('customer.feedback.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="feedbackText" class="form-label">Your Feedback</label>
                            <textarea name="message" class="form-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="feedbackText" rows="3" required><?php echo e(old('message')); ?></textarea>
                            <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <button type="submit" class="btn btn-zopa">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if($feedbacks->count()): ?>
    <div class="row mt-4">
        <div class="col-sm-12">
            <h5>Your Feedbacks</h5>
            <?php $__currentLoopData = $feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card shadow mb-3">
                <div class="card-body">
                    <p class="mb-1">
                        <strong><?php echo e($feedback->customer->name ?? 'Customer'); ?></strong>
                        <small class="text-muted float-end"><?php echo e($feedback->created_at->diffForHumans()); ?></small>
                    </p>
                    <p class="text-muted mb-1"><?php echo e($feedback->message); ?></p>

                    <?php if($feedback->reply): ?>
                    <div class="border-start ps-3 mt-2">
                        <strong class="text-success">Admin Reply:</strong>
                        <p class="mb-0"><?php echo e($feedback->reply); ?></p>
                        <small class="text-muted"><?php echo e($feedback->replied_at ? $feedback->replied_at->format('d M Y, h:i A') : ''); ?></small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <!-- Pagination -->
            <div class="mt-4">
                <?php echo e($feedbacks->links()); ?>

            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function showToast(message, type = 'success') {
            let toast = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('.flash-messages').prepend(toast);
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\feedbacks.blade.php ENDPATH**/ ?>