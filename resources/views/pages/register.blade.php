@extends('layouts.out')

@section('title', 'Signup - Zopa Food Drop')

@section('content')
<div class="container d-flex align-items-center justify-content-center py-4">
    <div class="card shadow overflow-auto" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <div class="text-center mb-4 logo_bg pb-4 pt-4">
                <a href="{{ route('index') }}">
                    <img src="{{ asset('front/images/logo.png') }}" alt="Zopa Food Drop" style="height: 100px; max-width: 100%;">
                </a>
            </div>
            <h4 class="card-title text-center mb-4">Signup</h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="registerForm" action="{{ route('front.register.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Personal Information --}}
                <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5" name="name" id="name" placeholder="Name" value="{{ old('name') }}">
                    <i class="fa fa-user input-icon"></i>
                </div>

                <div class="mb-1 position-relative has-tooltip">
                    <input type="text" class="form-control pe-5" name="phone" id="phone" placeholder="Mobile Number" value="{{ old('phone') }}">
                    <i class="fa fa-phone input-icon"></i>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" name="has_whatsapp" id="has_whatsapp" value="1" {{ old('has_whatsapp') ? 'checked' : '' }}>
                        <label class="form-check-label text-success" for="has_whatsapp">
                            Has WhatsApp
                        </label>
                    </div>

                    <div id="recaptcha-container" class="me-2"></div>

                    <a href="#" id="sendOtp" class="text-primary"><small>Send OTP</small></a>
                </div>
                <div id="otp-container" class="mb-3" style="display: none;">
                    <input type="text" class="form-control pe-5" name="otp" id="otp" placeholder="Enter OTP" />
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small id="otp-message" class="text-success"></small>
                        <small id="resend-wrapper" class="text-end">
                            <span id="resend-timer" class="text-muted"></span>
                            <a href="#" id="resendOtp" class="text-primary ms-2" style="display: none;">Resend OTP</a><br>
                            <small id="resend-attempts-left" class="text-muted"></small>
                        </small>
                    </div>
                </div>

                {{-- <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5" name="whatsapp" id="whatsapp" placeholder="WhatsApp Number" value="{{ old('whatsapp') }}">
                    <i class="fab fa-whatsapp input-icon"></i>
                </div> --}}

                <div class="mb-3 position-relative has-tooltip">
                    <input type="password" class="form-control pe-5" name="password" placeholder="Password" value="">
                    <i class="fa fa-lock input-icon"></i>
                    <button type="button" class="input-tooltip-btn" data-bs-toggle="tooltip" title="Password must be at least 8 characters, include 1 uppercase, 1 lowercase, 1 number, and 1 special character">
                        <i class="fa fa-info-circle text-primary"></i>
                    </button>
                </div>

                {{-- Office / Delivery Information --}}
                {{--                 <hr>

                <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5" name="office_name" id="office_name" placeholder="Shop/Office Name" value="{{ old('office_name') }}">
                    <i class="fa fa-building input-icon"></i>
                </div>

                <div class="mb-3 position-relative has-tooltip">
                    <input type="text" class="form-control pe-5" name="city" id="city" placeholder="Shop/Office Location" value="{{ old('city') }}">
                    <i class="fa fa-map-marker input-icon"></i>
                    <button type="button" class="input-tooltip-btn" data-bs-toggle="tooltip" title="Meals delivered Here">
                        <i class="fa fa-info-circle text-primary"></i>
                    </button>
                </div>

                <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5" name="landmark" placeholder="Landmark" value="{{ old('landmark') }}">
                    <i class="fa fa-location-arrow input-icon"></i>
                </div>

                <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5" name="designation" id="designation" placeholder="Job Designation" value="{{ old('designation') }}">
                    <i class="fa fa-briefcase input-icon"></i>
                </div>

                <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5" name="postal_code" id="postal_code" placeholder="Postal Code" value="{{ old('postal_code') }}">
                    <i class="fa fa-envelope input-icon"></i>
                </div> --}}

                {{-- Uncomment if you want to enable state/district selection --}}
                {{--
                <div class="mb-3 position-relative">
                    <select name="state_id" id="state_id" class="form-control select2 pe-5" onchange="getDistrict(this.value, 0);">
                        <option value="" disabled selected>Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ $state->id == Utility::STATE_ID_KERALA ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-map input-icon"></i>
                </div>

                <div class="mb-3 position-relative">
                    <select name="district_id" id="district-list" class="form-control select2 pe-5">
                        <option value="" disabled selected>Select District</option>
                    </select>
                    <i class="fa fa-map-pin input-icon"></i>
                </div> --}}
                {{-- Nearest Kitchen --}}
                {{--                 <hr>

                <div class="mb-3 position-relative">
                    <select id="kitchen_id" name="kitchen_id" class="form-control select2 pe-5">
                        <option value="" disabled selected>Select Nearest Zopa Kitchen</option>
                        @foreach ($kitchens as $kitchen)
                            <option value="{{ encrypt($kitchen->id) }}" {{ (old('kitchen_id') == encrypt($kitchen->id) || (Utility::KITCHEN_KDY == $kitchen->id && !old('kitchen_id'))) ? 'selected' : '' }}>
                                {{ $kitchen->name }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fa fa-cutlery input-icon"></i>
                </div> --}}

                <button type="submit" id="verifyOtp" class="btn btn-zopa w-100">Verify OTP & Signup</button>
            </form>

            <div class="text-center mt-3">
                <small>Already have an account? <a href="{{ route('customer.login') }}">Login</a></small><br>
                <small>Back to the <a href="{{ route('index') }}">Home Page </a></small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
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
@endpush

@push('scripts')
<!-- Firebase App (the core Firebase SDK) -->
<script src="https://www.gstatic.com/firebasejs/9.6.11/firebase-app-compat.js"></script>

<!-- Firebase Authentication -->
<script src="https://www.gstatic.com/firebasejs/9.6.11/firebase-auth-compat.js"></script>

<script>
    let countdownInterval;
    let countdown = 60;
    let resendAttempts = 0;
    const MAX_RESEND_ATTEMPTS = 2;
    let otpFailed = false;

    function updateAttemptsLeft() {
        const remaining = MAX_RESEND_ATTEMPTS - resendAttempts;
        const text = remaining > 0 ? `(${remaining} resend${remaining > 1 ? 's' : ''} left)` : "No resends left";
        document.getElementById("resend-attempts-left").textContent = text;
    }

    function startResendTimer() {
        countdown = 60;
        document.getElementById("resendOtp").style.display = "none";
        document.getElementById("resend-timer").textContent = `Resend OTP in ${countdown}s`;

        countdownInterval = setInterval(() => {
            countdown--;
            if (countdown > 0) {
                document.getElementById("resend-timer").textContent = `Resend OTP in ${countdown}s`;
            } else {
                clearInterval(countdownInterval);

                // Show "Resend OTP" only if attempts < MAX
                if (resendAttempts < MAX_RESEND_ATTEMPTS) {
                    document.getElementById("resendOtp").style.display = "inline";
                    document.getElementById("resend-timer").textContent = "";
                } else {
                    document.getElementById("resend-timer").textContent = "Max OTP attempts reached";
                    document.getElementById("resendOtp").style.display = "none";
                }
            }
        }, 1000);
    }

    // Shared function
    function sendOtp(isResend = false) {
        const phoneInput = document.getElementById("phone");
        const phone = phoneInput.value.trim();

        if (!phone.match(/^[6-9]\d{9}$/)) {
            alert("Enter a valid 10-digit Indian mobile number.");
            return;
        }

        // Show loading overlay
        document.getElementById("loading-overlay").style.display = "flex";


        // Step 1: Check if the customer exists
        fetch("{{ route('front.customer.exists') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": '{{ csrf_token() }}'
            },
            body: JSON.stringify({ phone })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                alert("An account with this phone number already exists. Please login.");
                window.location.href = '{{ route('customer.login') }}';
            } else {
                const phoneNumber = "+91" + phone;

                firebase.auth().signInWithPhoneNumber(phoneNumber, recaptchaVerifier)
                    .then(function (result) {
                        confirmationResult = result;

                        if (!isResend) {
                            phoneInput.disabled = true; // Disable phone on first attempt
                            document.getElementById("sendOtp").style.display = "none";
                            $('#otp-container').fadeIn();
                        }

                        otpFailed = false; // reset fail flag
                        document.getElementById("otp-message").textContent = "OTP sent successfully!";
                        updateAttemptsLeft();
                        startResendTimer();
                    }).catch(function (error) {
                        otpFailed = true;
                        alert("OTP Error: " + error.message);

                        // Re-enable phone if first send or final resend fails
                        if (!confirmationResult || resendAttempts >= MAX_RESEND_ATTEMPTS) {
                            document.getElementById("phone").disabled = false;
                        }
                    })
                    .finally(() => {
                        document.getElementById("loading-overlay").style.display = "none";
                    });
            }
        })
        .catch(error => {
            console.error("Error checking customer:", error);
            alert("Something went wrong. Please try again.");
        })
        .finally(() => {
            // Hide overlay even if error occurs in fetch
            document.getElementById("loading-overlay").style.display = "none";
        });

    }
</script>

<script>
    const firebaseConfig = @json($firebaseConfig);
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


    // Handle initial OTP send
    document.getElementById("sendOtp").addEventListener("click", function (e) {
        e.preventDefault();
        sendOtp();
    });

    // Handle resend
    document.getElementById("resendOtp").addEventListener("click", function (e) {
        e.preventDefault();

        // Block if max attempts reached (failsafe)
        if (resendAttempts >= MAX_RESEND_ATTEMPTS) return;

        resendAttempts++;
        updateAttemptsLeft();
        sendOtp(true);
    });
</script>
<script>
    // function getDistrict(stateId, selectedDistrictId = 0) {
    //     $.ajax({
    //         type: 'POST',
    //         url: "{{ route('get.districts') }}",
    //         data: { s_id: stateId, d_id: selectedDistrictId, _token: '{{ csrf_token() }}' },
    //         success: function(data) {
    //             $('#district-list').html(data);
    //         }
    //     });
    // }

    $(document).ready(function() {
        // Load districts on page load
        // getDistrict({{ Utility::STATE_ID_KERALA }}, 0);

        $('[data-bs-toggle="tooltip"]').tooltip();

        $('form#registerForm').on('submit', function(event) {
            event.preventDefault();
            // Enable the phone field so it gets submitted
            $('#phone').prop('disabled', false);

            let form = $(this);
            let submitButton = form.find('button[type=submit]');
            let isValid = true;
            let requiredFields = ["name", "phone", "otp", "password"]; //, "office_name", "city", "postal_code", "kitchen_id"

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
            ['phone'].forEach(name => {
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

            const otp = $('#otp').val().trim();

            if (!otp) {
                alert('Please Get & Enter the OTP.');
                return;
            }

            if (!isValid) {
                return;
            }

            if (!confirmationResult) {
                alert('Please send OTP first.');
                return;
            }

            confirmationResult.confirm(otp).then(function (result) {
                const user = result.user;
                // console.log("Verified UID: ", user.uid);
                // alert("Verified UID: " + user.uid);

                // Optionally attach Firebase UID in hidden field if needed
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "firebase_uid";
                input.value = user.uid;
                document.getElementById('registerForm').appendChild(input);

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

            }).catch(function (error) {
                alert("Invalid OTP: " + error.message);
            });

        });
    });
</script>
@endpush
