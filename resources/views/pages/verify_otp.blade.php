@extends('layouts.out')

@section('title', 'Verify OTP - ' . config('app.name'))

@section('content')
<div class="container d-flex align-items-center justify-content-center py-4">
    <div class="card shadow overflow-auto" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <div class="text-center mb-4 logo_bg pb-4 pt-4">
                <img src="{{ asset('front/images/logo.png') }}" alt="@appName" style="height: 100px; max-width: 100%;">
            </div>
            <h4 class="card-title text-center mb-4">OTP Verification</h4>

            <p class="text-center text-muted">Verifying phone number: {{ $customer->phone }}</p>

            <div id="recaptcha-container" class="mb-3 text-center"></div>

            <div class="mb-3 text-center">
                <button id="send-otp-btn" class="btn btn-zopa w-100">Send OTP</button>
            </div>

            <div class="mb-3 text-center d-none" id="otp-section">
                <input type="text" id="otp" class="form-control text-center mb-2" placeholder="Enter OTP" maxlength="6">
                <button id="verify-otp-btn" class="btn btn-zopa w-100">Verify OTP</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-auth-compat.js"></script>

<script>
    const firebaseConfig = {
        apiKey: "{{ env('FIREBASE_API_KEY') }}",
        authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
        projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
        storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
        messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
        appId: "{{ env('FIREBASE_APP_ID') }}"
    };

    firebase.initializeApp(firebaseConfig);
    const auth = firebase.auth();
    let confirmationResult;

    $(document).ready(function () {
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            'size': 'normal',
            'callback': function (response) {
                $('#send-otp-btn').prop('disabled', false);
            }
        });

        $('#send-otp-btn').on('click', function () {
            const phoneNumber = "+91{{ $customer->phone }}";
            auth.signInWithPhoneNumber(phoneNumber, window.recaptchaVerifier)
                .then((result) => {
                    confirmationResult = result;
                    $('#otp-section').removeClass('d-none');
                    $('#send-otp-btn').text('OTP Sent').prop('disabled', true);
                }).catch((error) => {
                    alert('Error sending OTP: ' + error.message);
                });
        });

        $('#verify-otp-btn').on('click', function () {
            const code = $('#otp').val();
            if (!code || code.length !== 6) {
                alert('Please enter a valid 6-digit OTP.');
                return;
            }

            confirmationResult.confirm(code).then((result) => {
                result.user.getIdToken().then((token) => {
                    $.post("{{ route('verify.otp') }}", {
                        _token: "{{ csrf_token() }}",
                        firebase_token: token
                    }).done((response) => {
                        if (response.redirect_url) {
                            window.location.href = response.redirect_url;
                        }
                    }).fail((xhr) => {
                        alert(xhr.responseJSON?.errors?.otp?.[0] || 'Verification failed.');
                    });
                });
            }).catch((error) => {
                alert('Invalid OTP: ' + error.message);
            });
        });
    });
</script>
@endpush
