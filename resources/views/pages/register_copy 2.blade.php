@extends('layouts.out')

@section('title', 'Signup - Zopa Food Drop')

@section('content')
<div class="container d-flex align-items-center justify-content-center py-4">
    <div class="card shadow overflow-auto" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <div class="text-center mb-4 logo_bg pb-4 pt-4">
                <a href="{{ route('index') }}"><img src="{{ asset('front/images/logo.png') }}" alt="Zopa Food Drop" style="height: 100px; max-width: 100%;"></a>
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
                    <input type="text" class="form-control pe-5" name="name" id="name" placeholder="Name" value="">
                    <i class="fa fa-user input-icon"></i>
                </div>

                <div class="mb-3 position-relative has-tooltip">
                    <input type="text" class="form-control pe-5" name="phone" id="phone" placeholder="Mobile Number" value="">
                    <i class="fa fa-phone input-icon"></i>
                    {{-- <button type="button" class="input-tooltip-btn" data-bs-toggle="tooltip" title="This is your username to login">
                        <i class="fa fa-info-circle text-primary"></i>
                    </button> --}}
                </div>

                <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5" name="whatsapp" id="whatsapp" placeholder="WhatsApp Number" value="">
                    <i class="fab fa-whatsapp input-icon"></i>
                </div>

                <div class="mb-3 position-relative has-tooltip">
                    <input type="password" class="form-control pe-5" name="password" placeholder="Password" value="">
                    <i class="fa fa-lock input-icon"></i>
                    <button type="button" class="input-tooltip-btn" data-bs-toggle="tooltip" title="Password must be at least 8 characters, include 1 uppercase, 1 lowercase, 1 number, and 1 special character">
                        <i class="fa fa-info-circle text-primary"></i>
                    </button>
                </div>


                <hr>

                {{-- Office / Delivery Information --}}
                <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5" name="office_name" id="office_name" placeholder="Shop/Office Name" value="">
                    <i class="fa fa-building input-icon"></i>
                </div>

                {{-- Shop/Office Location with tooltip info inside input --}}
                <div class="mb-3 position-relative has-tooltip">
                    <input type="text" class="form-control pe-5" name="city" id="city" placeholder="Shop/Office Location" value="">
                    <i class="fa fa-map-marker input-icon"></i>
                    <button type="button" class="input-tooltip-btn" data-bs-toggle="tooltip" title="Meals delivered Here">
                        <i class="fa fa-info-circle text-primary"></i>
                    </button>
                </div>

                <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5" name="landmark" placeholder="Landmark" value="">
                    <i class="fa fa-location-arrow input-icon"></i>
                </div>

                <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5" name="designation" id="designation" placeholder="Job Designation" value="">
                    <i class="fa fa-briefcase input-icon"></i>
                </div>

                <div class="mb-3 position-relative">
                    <input type="text" class="form-control pe-5" name="postal_code" id="postal_code" placeholder="Postal Code" value="">
                    <i class="fa fa-envelope input-icon"></i>
                </div>

                {{-- <div class="mb-3 position-relative">
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

                <hr>

                {{-- Nearest Kitchen --}}
                <div class="mb-3 position-relative">
                    <select id="kitchen_id" name="kitchen_id" class="form-control select2 pe-5">
                        <option value="" disabled selected>Select Nearest Zopa Kitchen</option>
                        @foreach ($kitchens as $kitchen)
                            <option value="{{ encrypt($kitchen->id) }}" {{ (Utility::KITCHEN_KDY == $kitchen->id) ? 'selected' : '' }}>{{ $kitchen->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-cutlery input-icon"></i>
                </div>

                <button type="submit" class="btn btn-zopa w-100">Signup</button>
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
    });

    $(document).ready(function() {
        $('form#registerForm').on('submit', function(event) {
            event.preventDefault();

            let form = $(this);
            let submitButton = form.find('button[type=submit]');
            let isValid = true;
            let requiredFields = ["name", "phone", "password", "office_name", "city", "whatsapp", "postal_code", "kitchen_id"];

            // Clear previous errors
            form.find('input, select').removeClass('invalid');
            form.find('.alert.alert-danger').remove();

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

            // Validate Indian phone number format (starts with 6-9, exactly 10 digits)
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
@endpush
