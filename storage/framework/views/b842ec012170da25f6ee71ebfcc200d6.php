<?php $__env->startSection('title', 'Signup - Zopa Food Drop'); ?>

<?php $__env->startSection('content'); ?>
<div class="container d-flex align-items-center justify-content-center py-4">
    <div class="card shadow overflow-auto" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <div class="text-center mb-4 logo_bg pb-4 pt-4">
                <a href="<?php echo e(route('index')); ?>">
                    <img src="<?php echo e(asset('front/images/logo.png')); ?>" alt="Zopa Food Drop" style="height: 100px; max-width: 100%;">
                </a>
            </div>
            <h4 class="card-title text-center mb-4">Signup</h4>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="registerForm" action="<?php echo e(route('front.register.submit')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                
                <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5" name="name" id="name" placeholder="Name" value="<?php echo e(old('name')); ?>">
                    <i class="fa fa-user input-icon"></i>
                </div>

                <div class="mb-1 position-relative has-tooltip">
                    <input type="text" class="form-control pe-5" name="phone" id="phone" placeholder="Mobile Number" value="<?php echo e(old('phone')); ?>">
                    <i class="fa fa-phone input-icon"></i>
                </div>
                <div class="mb-1 text-end">
                    <div id="recaptcha-container"></div>
                    <a href="#" id="sendOtp" class="text-primary "><small>Send OTP</small></a>
                </div>
                <div class="mb-3">
                    <!-- OTP input -->
                    <input type="text" class="form-control pe-5" name="otp" id="otp" placeholder="Enter OTP" />
                </div>

                <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5" name="whatsapp" id="whatsapp" placeholder="WhatsApp Number" value="<?php echo e(old('whatsapp')); ?>">
                    <i class="fab fa-whatsapp input-icon"></i>
                </div>

                <div class="mb-3 position-relative has-tooltip">
                    <input type="password" class="form-control pe-5" name="password" placeholder="Password" value="">
                    <i class="fa fa-lock input-icon"></i>
                    <button type="button" class="input-tooltip-btn" data-bs-toggle="tooltip" title="Password must be at least 8 characters, include 1 uppercase, 1 lowercase, 1 number, and 1 special character">
                        <i class="fa fa-info-circle text-primary"></i>
                    </button>
                </div>

                
                

                
                
                
                

                <button type="submit" id="verifyOtp" class="btn btn-zopa w-100">Verify OTP & Signup</button>
            </form>

            <div class="text-center mt-3">
                <small>Already have an account? <a href="<?php echo e(route('customer.login')); ?>">Login</a></small><br>
                <small>Back to the <a href="<?php echo e(route('index')); ?>">Home Page </a></small>
            </div>
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
        color: #6c757d;
        pointer-events: none;
    }

    /* Adjust input padding if input has tooltip button */
    .has-tooltip .form-control {
        padding-right: 65px !important; /* wider padding to fit both icon + tooltip */
    }

    /* Adjust input padding for normal icon-only case */
    .position-relative:not(.has-tooltip) .form-control {
        padding-right: 40px !important;
    }

    /* Tooltip button spacing — sit just left of icon */
    .input-tooltip-btn {
        position: absolute;
        right: 30px; /* 30px to leave space between tooltip and icon */
        top: 50%;
        transform: translateY(-50%);
        padding: 0;
        border: none;
        background: transparent;
        z-index: 3;
    }

    /* Optional — smaller tooltip icon size */
    .input-tooltip-btn i {
        font-size: 16px;
        color: #6c757d;
    }

    /* Optional — red border when input is invalid */
    .invalid {
        border-color: #dc3545 !important;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Firebase App (the core Firebase SDK) -->
<script src="https://www.gstatic.com/firebasejs/9.6.11/firebase-app-compat.js"></script>

<!-- Firebase Authentication -->
<script src="https://www.gstatic.com/firebasejs/9.6.11/firebase-auth-compat.js"></script>


<script>
    const firebaseConfig = <?php echo json_encode($firebaseConfig, 15, 512) ?>;
    firebase.initializeApp(firebaseConfig);

    let confirmationResult;
    let recaptchaVerifier;

    // Initialize ReCAPTCHA on load
    window.onload = function () {
        recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            size: 'invisible',
            callback: function (response) {
                // reCAPTCHA solved, proceed with OTP
            }
        });

        recaptchaVerifier.render(); // Important!
    };

    // Handle OTP Send
    document.getElementById("sendOtp").addEventListener("click", function (e) {
        e.preventDefault();

        const phone = document.getElementById("phone").value.trim();
        if (!phone.match(/^[6-9]\d{9}$/)) {
            alert("Enter a valid 10-digit Indian mobile number.");
            return;
        }

        const phoneNumber = "+91" + phone;

        firebase.auth().signInWithPhoneNumber(phoneNumber, recaptchaVerifier)
            .then(function (result) {
                confirmationResult = result;
                alert("OTP sent successfully!");
            }).catch(function (error) {
                alert("OTP Error: " + error.message);
            });
    });
</script>
<script>
    // function getDistrict(stateId, selectedDistrictId = 0) {
    //     $.ajax({
    //         type: 'POST',
    //         url: "<?php echo e(route('get.districts')); ?>",
    //         data: { s_id: stateId, d_id: selectedDistrictId, _token: '<?php echo e(csrf_token()); ?>' },
    //         success: function(data) {
    //             $('#district-list').html(data);
    //         }
    //     });
    // }

    $(document).ready(function() {
        // Load districts on page load
        // getDistrict(<?php echo e(Utility::STATE_ID_KERALA); ?>, 0);

        $('[data-bs-toggle="tooltip"]').tooltip();

        $('form#registerForm').on('submit', function(event) {
            event.preventDefault();

            let form = $(this);
            let submitButton = form.find('button[type=submit]');
            let isValid = true;
            let requiredFields = ["name", "phone", "otp", "password", "whatsapp"]; //, "office_name", "city", "postal_code", "kitchen_id"

            // Clear previous errors
            form.find('input, select').removeClass('invalid');
            form.find('.alert.alert-danger').remove();
            form.find('.form-control').removeClass('invalid');

            // Trim all text inputs
            form.find('input[type=text]').each(function() {
                $(this).val($.trim($(this).val()));
            });

            // Validate required fields
            requiredFields.forEach(name => {
                let input = form.find(`[name="${name}"]`);
                if (!input.val() || !input.val().trim()) {
                    input.addClass("invalid");
                    isValid = false;
                }
            });

            // Validate Indian phone number format (starts with 5-9, exactly 10 digits)
            const phonePattern = /^[5-9]\d{9}$/;
            ['phone', 'whatsapp'].forEach(name => {
                let input = form.find(`[name="${name}"]`);
                if (!phonePattern.test(input.val())) {
                    input.addClass("invalid");
                    isValid = false;
                }
            });

            // Validate password: min 8, one uppercase, one lowercase, one digit, one special char
            const passwordInput = form.find('[name="password"]');
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordPattern.test(passwordInput.val())) {
                passwordInput.addClass("invalid");
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            const otp = $('#otp').val().trim();

            if (!confirmationResult) {
                alert('Please send OTP first.');
                return;
            }

            confirmationResult.confirm(otp).then(function (result) {
                const user = result.user;
                // console.log("Verified UID: ", user.uid);
                // alert("Verified UID: ", user.uid);

                Optionally attach Firebase UID in hidden field if needed
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "firebase_uid";
                input.value = user.uid;
                document.getElementById('registerForm').appendChild(input);

            }).catch(function (error) {
                alert("Invalid OTP: " + error.message);
            });

            // If valid, disable button and show "Progress..."
            submitButton.prop('disabled', true).text('Progress...');

            // Submit form via AJAX
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else {
                        alert('Registration successful!');
                        window.location.reload();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorList = '<ul class="mb-0">';
                        $.each(errors, function(key, messages) {
                            errorList += '<li>' + messages[0] + '</li>';
                            form.find(`[name="${key}"]`).addClass('invalid');
                        });
                        errorList += '</ul>';
                        form.prepend('<div class="alert alert-danger">' + errorList + '</div>');
                    } else {
                        alert('An unexpected error occurred. Please try again.');
                    }
                },
                complete: function() {
                    submitButton.prop('disabled', false).text('Signup');
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.out', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\register.blade.php ENDPATH**/ ?>