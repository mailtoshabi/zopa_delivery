@extends('layouts.out')

@section('title', 'Login via OTP - ' . config('app.name'))

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
    <div class="card shadow" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <div class="text-center mb-4 logo_bg pb-4 pt-4">
                <a href="{{ route('index') }}">
                    <img src="{{ asset('front/images/logo.png') }}" alt="@appName" style="height: 100px;">
                </a>
            </div>

            <h4 class="card-title text-center mb-3">Login via OTP</h4>
            <div id="alert-box"></div>

            <div class="mb-3 position-relative">
                <input type="text" class="form-control" id="phone" placeholder="Mobile Number">
                <i class="fa fa-phone input-icon"></i>
            </div>

            <div id="otp-container" style="display: none;">
                <div class="mb-3 position-relative">
                    <input type="text" class="form-control" id="otp" placeholder="Enter OTP">
                    <i class="fa fa-key input-icon"></i>
                </div>
                <button type="button" class="btn btn-success w-100 mb-2" onclick="verifyOtpAndLogin()">Verify OTP</button>
                <p id="otp-message" class="text-success text-center small mb-0"></p>
                <p id="resend-timer" class="text-muted text-center small"></p>
                <button id="resendOtp" class="btn btn-link btn-sm w-100" onclick="sendOtp(true)" style="display: none;">Resend OTP</button>
            </div>

            <button type="button" id="sendOtp" class="btn btn-zopa w-100 mt-2" onclick="sendOtp()">Send OTP</button>
            <div id="loading-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.6); z-index:9999;">
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <small><a href="{{ route('customer.login') }}">Login with password</a></small><br>
                <small>Back to the <a href="{{ route('index') }}">Home Page</a></small>
            </div>
        </div>
    </div>
</div>

<!-- Recaptcha -->
<div id="recaptcha-container"></div>
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
</style>
@endpush

@push('scripts')
<!-- Firebase Scripts -->
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-auth-compat.js"></script>

<script>
    const firebaseConfig = {
        apiKey: "AIzaSyAOBU6F4nUNCAXMt8uR99CNPNS4sx7NgeY",
        authDomain: "phone-verification-42c4a.firebaseapp.com",
        projectId: "phone-verification-42c4a",
        storageBucket: "phone-verification-42c4a.firebasestorage.app",
        messagingSenderId: "808352569846",
        appId: "1:808352569846:web:4a213e04750684308f4fd7"
    };

    firebase.initializeApp(firebaseConfig);

    let confirmationResult;
    let resendAttempts = 0;
    const MAX_RESEND = 3;
    let timerInterval;

    function showLoading(show = true) {
        document.getElementById("loading-overlay").style.display = show ? "block" : "none";
    }

    function sendOtp(isResend = false) {
        const phoneInput = document.getElementById("phone");
        const phone = phoneInput.value.trim();

        if (!phone.match(/^[6-9]\d{9}$/)) {
            alert("Enter a valid 10-digit Indian mobile number.");
            return;
        }

        showLoading(true);

        // Step 1: Check if customer exists
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
            if (!data.exists) {
                showLoading(false);
                alert("No account found with this number. Please register first.");
                return;
            }

            const fullPhone = "+91" + phone;

            // Step 2: Always re-initialize reCAPTCHA before each OTP send
            const recaptchaContainer = document.getElementById('recaptcha-container');
            recaptchaContainer.innerHTML = ""; // Clear old widget if any

            const recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                size: 'invisible',
                callback: function(response) {
                    console.log('reCAPTCHA solved:', response);
                },
                'expired-callback': function () {
                    alert('reCAPTCHA expired. Please try again.');
                }
            });

            recaptchaVerifier.render().then(function (widgetId) {
                window.recaptchaWidgetId = widgetId;

                firebase.auth().signInWithPhoneNumber(fullPhone, recaptchaVerifier)
                    .then(result => {
                        confirmationResult = result;

                        if (!isResend) {
                            phoneInput.disabled = true;
                            document.getElementById("sendOtp").style.display = "none";
                            document.getElementById("otp-container").style.display = "block";
                        }

                        document.getElementById("otp-message").textContent = "OTP sent successfully!";
                        resendAttempts++;
                        updateResendTimer();
                    })
                    .catch(error => {
                        console.error(error);
                        alert("Error sending OTP: " + error.message);
                        phoneInput.disabled = false;
                    })
                    .finally(() => showLoading(false));
            });
        })
        .catch(error => {
            console.error("Validation error", error);
            alert("Failed to validate number. Try again.");
            showLoading(false);
        });
    }

    function updateResendTimer() {
        let seconds = 60;
        const resendBtn = document.getElementById("resendOtp");
        const timerText = document.getElementById("resend-timer");
        resendBtn.style.display = "none";
        timerText.textContent = `You can resend OTP in ${seconds} seconds`;

        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            seconds--;
            if (seconds <= 0) {
                clearInterval(timerInterval);
                timerText.textContent = "";
                if (resendAttempts < MAX_RESEND) {
                    resendBtn.style.display = "inline";
                }
            } else {
                timerText.textContent = `You can resend OTP in ${seconds} seconds`;
            }
        }, 1000);
    }

    function verifyOtpAndLogin() {
        const otp = document.getElementById("otp").value.trim();
        const phone = document.getElementById("phone").value.trim();

        if (!otp || !confirmationResult) {
            alert("Please enter the OTP.");
            return;
        }

        showLoading(true);

        confirmationResult.confirm(otp)
            .then(() => {
                // Tell Laravel to log in
                fetch("{{ route('customer.front.login.otp.verify') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ phone })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        alert("Login failed.");
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert("Verification failed. Try again.");
                })
                .finally(() => showLoading(false));
            })
            .catch(error => {
                console.error(error);
                alert("OTP Verification failed.");
                showLoading(false);
            });
    }
</script>
@endpush
