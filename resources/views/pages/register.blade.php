@extends('layouts.out')

@section('title', 'Signup - ' . config('app.name'))

@section('content')
<div class="container d-flex align-items-center justify-content-center py-4">
    <div class="card shadow overflow-auto" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <div class="text-center mb-4 logo_bg pb-4 pt-4">
                <a href="{{ route('index') }}">
                    <img src="{{ asset('front/images/logo.png') }}" alt="@appName" style="height: 100px; max-width: 100%;">
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

                <div class="mb-3 position-relative has-tooltip">
                    <input type="password" class="form-control pe-5" name="password" placeholder="Password" value="">
                    <i class="fa fa-lock input-icon"></i>
                    <button type="button" class="input-tooltip-btn" data-bs-toggle="tooltip" title="Password must be at least 8 characters, include 1 uppercase, 1 lowercase, 1 number, and 1 special character">
                        <i class="fa fa-info-circle text-primary"></i>
                    </button>
                </div>

                <!-- Input group for input + tooltip button -->
                <div class="input-group mb-2">
                    <input type="text" id="autocomplete" placeholder="Enter location" class="form-control" value="{{ isset($customer)?$customer->location_name:old('location_name')}}">
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        onclick="getCurrentLocation()"
                        data-bs-toggle="tooltip"
                        data-bs-placement="left"
                        title="Use current location">
                        📍
                    </button>
                    @error('location_name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <!-- Hidden fields -->
                <input type="hidden" name="latitude" id="latitude" value="{{ isset($customer)?$customer->latitude:old('latitude')}}">
                <input type="hidden" name="longitude" id="longitude" value="{{ isset($customer)?$customer->longitude:old('longitude')}}">
                <input type="hidden" name="location_name" id="location_name" value="{{ isset($customer)?$customer->location_name:old('location_name')}}">
                <!-- Map -->
                <div class="mb-3 d-none" id="map" style="height: 300px; width: 100%; margin-top: 10px;"></div>

                <div class="mb-3 d-none position-relative" id="kitchenContainer">
                    <label for="kitchen_id" class="form-label">Nearest Kitchen <span class="text-danger">*</span></label>
                    <select id="kitchen_id" name="kitchen_id" class="form-control">
                        <option value="">Select Kitchen</option>
                    </select>
                    <small id="kitchenMessage" class="text-danger d-none">No nearby kitchen found. Contact our <a href="{{ route('support') }}">Support</a></small>
                </div>

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

    $(document).ready(function() {
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

            // ✅ Validate daily_quantity only if customer_type is institution
            let customerType = form.find('[name="customer_type"]').val();
            let dailyQtyInput = form.find('[name="daily_quantity"]');
            if (customerType === '{{ Utility::CUSTOMER_TYPE_INST }}') {
                let qty = dailyQtyInput.val();
                if (!qty || isNaN(qty) || parseInt(qty) < 1) {
                    dailyQtyInput.addClass("invalid");
                    isValid = false;
                }
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

        form.find('input, select, textarea').on('input change', function () {
            $(this).removeClass('invalid');
        });
    });
</script>

<script>
    $(document).ready(function() {
        document.getElementById('customer_type').addEventListener('change', function () {
            document.getElementById('daily_quantity_group').style.display =
                this.value === '{{ Utility::CUSTOMER_TYPE_INST }}' ? 'block' : 'none';
                document.getElementById('daily_quantity').focus();
        });
    });
</script>

<script>
    function fetchNearbyKitchens(lat, lng) {
        $.ajax({
            url: '{{ route("get.nearby.kitchens") }}',
            method: 'GET',
            data: { latitude: lat, longitude: lng },
            success: function (data) {
                const dropdown = $('#kitchen_id');
                const message = $('#kitchenMessage');
                dropdown.empty();
                console.log(data);
                if (data.length > 0) {
                    dropdown.append('<option value="">Select Kitchen</option>');
                    data.forEach(function (kitchen, index) {
                        dropdown.append(
                            `<option value="${kitchen.encrypted_id}" ${index === 0 ? 'selected' : ''}>
                                ${kitchen.display_name} (${kitchen.distance.toFixed(2)} km)
                            </option>`
                        );
                    });
                    dropdown.prop('disabled', false);
                    message.addClass('d-none');
                } else {
                    dropdown.append('<option value="">No kitchen found</option>');
                    dropdown.prop('disabled', true);
                    message.removeClass('d-none');
                }
            },
            error: function (e) {
                console.log(e);
                alert('Unable to fetch nearby kitchens. Please try again.');
            }
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBlsEVjPfChYExnraJoKmt7aG7ItrPZ9TA&libraries=places"></script>
<script>
    let map;
    let marker;

    function initAutocomplete() {
        let input = document.getElementById('autocomplete');
        let autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.setFields(['geometry', 'formatted_address']);

        const defaultLat = 10.8505;  // Fallback default
        const defaultLng = 76.2711;
        const lat = parseFloat(document.getElementById('latitude').value) || defaultLat;
        const lng = parseFloat(document.getElementById('longitude').value) || defaultLng;

        // Initialize map
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: lat, lng: lng },
            zoom: (lat !== defaultLat && lng !== defaultLng) ? 15 : 10
        });

        // Initialize marker
        marker = new google.maps.Marker({
            map: map,
            position: { lat: lat, lng: lng },
            draggable: true
        });

        // Update fields when marker is dragged
        marker.addListener('dragend', function () {
            const position = marker.getPosition();
            document.getElementById('latitude').value = position.lat();
            document.getElementById('longitude').value = position.lng();

            const geocoder = new google.maps.Geocoder();
            const lat = position.lat();
            const lng = position.lng();
            geocoder.geocode({ location: position }, function (results, status) {
                if (status === 'OK' && results[0]) {
                    document.getElementById('location_name').value = results[0].formatted_address;
                    document.getElementById('autocomplete').value = results[0].formatted_address;
                    fetchNearbyKitchens(parseFloat(lat), parseFloat(lng));
                }
            });
        });

        // Handle autocomplete selection
        autocomplete.addListener('place_changed', function () {
            $('#map').removeClass('d-none');
            $('#kitchenContainer').removeClass('d-none');
            const place = autocomplete.getPlace();
            if (!place.geometry) return;

            const location = place.geometry.location;
            map.setCenter(location);
            map.setZoom(15);
            marker.setPosition(location);

            const lat = location.lat();
            const lng = location.lng();

            $('#latitude').val(lat);
            $('#longitude').val(lng);
            $('#location_name').val(place.formatted_address);

            fetchNearbyKitchens(parseFloat(lat), parseFloat(lng));

        });
    }

    google.maps.event.addDomListener(window, 'load', initAutocomplete);



    function getCurrentLocation() {
        $('#map').removeClass('d-none');
        $('#kitchenContainer').removeClass('d-none');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                const latlng = new google.maps.LatLng(lat, lng);
                const geocoder = new google.maps.Geocoder();

                geocoder.geocode({ location: latlng }, (results, status) => {
                    if (status === "OK" && results[0]) {
                        document.getElementById('autocomplete').value = results[0].formatted_address;
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lng;
                        document.getElementById('location_name').value = results[0].formatted_address;

                        map.setCenter(latlng);
                        map.setZoom(15);
                        marker.setPosition(latlng);
                        fetchNearbyKitchens(parseFloat(lat), parseFloat(lng));
                    }
                });
            });

        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
</script>
@endpush
