@extends('layouts.out')

@section('title', 'Login - ' . config('app.name'))

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
    <div class="card shadow" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <div class="text-center mb-4 logo_bg pb-4 pt-4">
                <a href="{{ route('index') }}"><img src="{{ asset('front/images/logo.png') }}" alt="@appName" style="height: 100px; max-width: 100%;"></a>
            </div>
            <h4 class="card-title text-center mb-4">Login</h4>

            <div id="alert-box"></div>

            <form id="loginForm" action="{{ route('customer.login.submit') }}" method="POST">
                @csrf

                <div class="mb-3 position-relative">
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Mobile Number" value="">
                    <i class="fa fa-phone input-icon"></i>
                </div>

                <div class="mb-1 position-relative">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="">
                    <i class="fa fa-lock input-icon"></i>
                </div>
                <div class="mb-1 text-end"><small><a href="{{ route('customer.front.login.otp') }}" class="text-decoration-none"><small>Forgot password?</small></a></small></div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember" checked>
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>

                <button type="submit" class="btn btn-zopa w-100" id="loginBtn">Login</button>
            </form>

            <div class="text-center mt-3">
                <small>Don't have an account? <a href="{{ route('front.register') }}">Register</a></small><br>
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
    </style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('form#loginForm').on('submit', function(event) {
        event.preventDefault();

        let form = $(this);
        let submitButton = form.find('button[type=submit]');
        let isValid = true;
        let requiredFields = ["phone", "password"];

        // Clear previous errors
        form.find('input').removeClass('invalid');
        form.find('.alert.alert-danger').remove();

        // Trim all text inputs
        form.find('input[type=text], input[type=password]').each(function() {
            $(this).val($.trim($(this).val()));
        });

        // Validate required fields
        requiredFields.forEach(name => {
            let input = form.find(`[name="${name}"]`);
            let value = input.val().trim();

            if (!value) {
                input.addClass("invalid");
                isValid = false;
            }

            // Indian mobile number validation for phone
            if (name === "phone") {
                const phoneRegex = /^[5-9]\d{9}$/;
                if (!phoneRegex.test(value)) {
                    input.addClass("invalid");
                    isValid = false;
                }
            }
        });

        if (!isValid) {
            // Do not submit if invalid
            return;
        }

        // Disable button and show "Progress..."
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
                    alert('Login successful!');
                    window.location.reload();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Laravel validation error
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
                // Always re-enable button
                submitButton.prop('disabled', false).text('Login');
            }
        });
    });
});
</script>
@endpush
