<?php $__env->startSection('title', 'Verify OTP - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container d-flex align-items-center justify-content-center py-4">
    <div class="card shadow overflow-auto" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <div class="text-center mb-4 logo_bg pb-4 pt-4">
                <img src="<?php echo e(asset('front/images/logo.png')); ?>" alt="<?php echo config('app.name'); ?>" style="height: 100px; max-width: 100%;">
            </div>
            <h4 class="card-title text-center mb-4">OTP Verification</h4>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            <p>OTP sent to <?php echo e($customer->phone); ?></p>

            <form id="otpForm" action="<?php echo e(route('verify.otp')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="mb-3 text-center">
                    <p class="text-muted">Enter the 6-digit OTP sent to your mobile number</p>
                </div>

                <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5 text-center" name="otp" id="otp" maxlength="6" placeholder="Enter OTP">
                    <i class="fa fa-key input-icon"></i>
                </div>

                <button type="submit" class="btn btn-zopa w-100">Verify</button>

                <div class="text-center mt-3">
                    <small>Didn't receive the code? <a href="">Resend OTP</a></small>
                    
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .input-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #dc3545; /* red for error */
            pointer-events: none;
        }

        .position-relative .form-control {
            padding-right: 40px !important;
        }

        .invalid {
            border-color: #dc3545 !important;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        $('form#otpForm').on('submit', function(event) {
            event.preventDefault();

            let form = $(this);
            let submitButton = form.find('button[type=submit]');
            let otpInput = form.find('[name="otp"]');
            let isValid = true;

            // Clear previous errors
            otpInput.removeClass('invalid');
            form.find('.alert.alert-danger').remove();

            // Validate OTP (6-digit numeric)
            const otpPattern = /^\d{6}$/;
            if (!otpPattern.test(otpInput.val())) {
                otpInput.addClass('invalid');
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            submitButton.prop('disabled', true).text('Verifying...');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                console.log(response);
                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else {
                        alert('OTP verified!');
                        window.location.reload();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorList = '<ul class="mb-0">';
                        $.each(errors, function(key, messages) {
                            errorList += '<li>' + messages[0] + '</li>';
                            if (key === 'otp') {
                                otpInput.addClass('invalid');
                            }
                        });
                        errorList += '</ul>';
                        form.prepend('<div class="alert alert-danger">' + errorList + '</div>');
                    } else {
                        alert('An unexpected error occurred. Please try again.');
                    }
                },
                complete: function() {
                    submitButton.prop('disabled', false).text('Verify');
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.out', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\verify_otp_Old.blade.php ENDPATH**/ ?>