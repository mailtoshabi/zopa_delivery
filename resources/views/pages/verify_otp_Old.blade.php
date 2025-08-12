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

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p>OTP sent to {{ $customer->phone }}</p>

            <form id="otpForm" action="{{ route('verify.otp') }}" method="POST">
                @csrf

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
                    {{-- {{ route('front.otp.resend') }} --}}
                </div>
            </form>
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
@endpush

@push('scripts')
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
@endpush
