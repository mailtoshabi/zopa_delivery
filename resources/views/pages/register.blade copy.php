@extends('layouts.out')

@section('title', 'Signup - Zopa Food Drop')

@section('content')
<div class="container d-flex align-items-center justify-content-center py-4">
    <div class="card shadow overflow-auto" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <div class="text-center mb-4 logo_bg pb-4 pt-4">
                <img src="{{ asset('front/images/logo.png') }}" alt="Zopa Food Drop" style="height: 100px; max-width: 100%;">
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

                <div class="mb-3">
                    <label class="required-label">Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="Shabeer CM">
                </div>

                <div class="mb-3">
                    <label class="required-label">Mobile Number</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="9809373738">
                </div>

                <div class="mb-3">
                    <label class="required-label">Password</label>
                    <input type="password" class="form-control" name="password" value="123456">
                </div>

                <div class="mb-3">
                    <label class="required-label">Shop/Office Name</label>
                    <input type="text" class="form-control" name="office_name" id="office_name" value="Web Mahal Web Service">
                </div>

                <div class="mb-3">
                    <label class="required-label" data-bs-toggle="tooltip" title="Location, where the meals will be delivered.">
                        Shop/Office Location <i class="fa fa-info-circle"></i>
                    </label>
                    <input type="text" class="form-control" name="city" id="city" value="Pazhayangadi">
                </div>

                <div class="mb-3">
                    <label>Land Mark</label>
                    <input type="text" class="form-control" name="landmark" value="Opposite Kazhiyarakam Masjid">
                </div>

                <div class="mb-3">
                    <label>Job Designation</label>
                    <input type="text" class="form-control" name="designation" id="designation" value="Software developer">
                </div>

                <div class="mb-3">
                    <label>WhatsApp Number</label>
                    <input type="text" class="form-control" name="whatsapp" id="whatsapp" value="9809373738">
                </div>

                {{-- <div class="mb-3">
                    <label class="required-label">State</label>
                    <select name="state_id" id="state_id" class="form-control select2" onchange="getDistrict(this.value, 0);">
                        <option value="" disabled selected>Select a state</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ $state->id == Utility::STATE_ID_KERALA ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="required-label">District</label>
                    <select name="district_id" id="district-list" class="form-control select2">
                        <option value="" disabled selected>Select a district</option>
                    </select>
                </div> --}}

                <div class="mb-3">
                    <label class="required-label">Postal Code</label>
                    <input type="text" class="form-control" name="postal_code" id="postal_code" value="">
                </div>

                <hr>

                <div class="mb-3">
                    <label class="required-label">Nearest Zopa Kitchen</label>
                    <select id="kitchen_id" name="kitchen_id" class="form-control select2">
                        <option value="" disabled selected>Select a kitchen</option>
                        @foreach ($kitchens as $kitchen)
                            <option value="{{ encrypt($kitchen->id) }}" {{ (Utility::KITCHEN_KDY == $kitchen->id) ? 'selected' : '' }}>{{ $kitchen->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-zopa w-100">Signup</button>
            </form>

            <div class="text-center mt-3">
                <small>Already have an account? <a href="{{ route('index') }}">Login</a></small>
            </div>
        </div>
    </div>
</div>
@endsection

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

        $('form').on('submit', function() {
            $(this).find('button[type=submit]').prop('disabled', true).text('Progress...');
            $(this).find('input[type=text]').each(function() {
                $(this).val($.trim($(this).val()));
            });
        });
    });
</script>
@endpush
