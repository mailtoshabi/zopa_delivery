@extends('layouts.app')

@section('title', 'My Zopa Profile')

@section('content')
<div class="container my-4">
    <h2 class="mb-4">My Zopa Profile</h2>

    {{-- Profile View (Read-Only) --}}
    <div id="profileView">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Profile Details</span>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary" id="editProfileBtn">
                        <i class="fas fa-edit me-1"></i> Edit Profile
                    </button>
                    <a href="{{ route('customer.profile.password.change') }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-key me-1"></i> Change Password
                    </a>
                </div>

            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Full Name:</strong><br> {{ $customer->name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Shop/Office Name:</strong><br> {{ $customer->office_name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Job Designation:</strong><br> {{ $customer->designation ?? '-' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Whatsapp Number:</strong><br> {{ $customer->whatsapp ?? '-' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Shop/Office Location:</strong><br> {{ $customer->city }}
                    </div>
                    <div class="col-md-6">
                        <strong>Landmark:</strong><br> {{ $customer->landmark ?? '-' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Postal Code:</strong><br> {{ $customer->postal_code }}
                    </div>
                    <div class="col-md-6">
                        <strong>State:</strong><br> {{ optional($customer->state)->name }}
                    </div>
                    <div class="col-md-6">
                        <strong>District:</strong><br> {{ optional($customer->district)->name }}
                    </div>
                    <div class="col-md-6">
                        <strong>Profile Image:</strong><br>
                        @if(!empty($customer->image_filename))
                            <img src="{{ Storage::url(App\Models\Customer::DIR_PUBLIC.'/'.$customer->image_filename) }}" alt="Profile Image" class="rounded img-thumbnail mt-2" width="120">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Profile Edit Form --}}
    <div id="profileForm" style="display: none;">
        <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Edit Profile</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="cancelEditBtn">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                </div>
                <div class="card-body row g-3">

                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input id="name" name="name" type="text" class="form-control" placeholder="Full Name" value="{{ old('name', $customer->name) }}">
                        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="office_name" class="form-label">Shop/Office Name <span class="text-danger">*</span></label>
                        <input id="office_name" name="office_name" type="text" class="form-control" placeholder="Shop/Office Name" value="{{ old('office_name', $customer->office_name) }}">
                        @error('office_name') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="designation" class="form-label">Job Designation</label>
                        <input id="designation" name="designation" type="text" class="form-control" placeholder="Job Designation" value="{{ old('designation', $customer->designation) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="whatsapp" class="form-label">Whatsapp Number <small class="text-muted">(optional)</small></label>
                        <input id="whatsapp" name="whatsapp" type="text" class="form-control" placeholder="Whatsapp Number" value="{{ old('whatsapp', $customer->whatsapp) }}">
                        @error('whatsapp') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="city" class="form-label">Shop/Office Location <span class="text-danger">*</span></label>
                        <input id="city" name="city" type="text" class="form-control" placeholder="City" value="{{ old('city', $customer->city) }}">
                        @error('city') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="landmark" class="form-label">Landmark</label>
                        <input id="landmark" name="landmark" type="text" class="form-control" placeholder="Landmark" value="{{ old('landmark', $customer->landmark) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="postal_code" class="form-label">Postal Code <span class="text-danger">*</span></label>
                        <input id="postal_code" name="postal_code" type="text" class="form-control" placeholder="Postal Code" value="{{ old('postal_code', $customer->postal_code) }}">
                        @error('postal_code') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    {{-- <div class="col-md-6">
                        <label for="state_id" class="form-label">State <span class="text-danger">*</span></label>
                        <select id="state_id" name="state_id" class="form-select" onchange="getDistrict(this.value, {{ $customer->district_id ?? 0 }});">
                            <option value="">Select State</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}" {{ $state->id == ($customer->state_id ?? Utility::STATE_ID_KERALA) ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('state_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="district_id" class="form-label">District <span class="text-danger">*</span></label>
                        <select id="district-list" name="district_id" class="form-select">
                            <option value="">Select District</option>
                        </select>
                        @error('district_id') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div> --}}

                    <div class="col-md-6">
                        <label class="form-label">Profile Image</label>
                        <div class="text-center">
                            <span id="imageContainer" @if(empty($customer->image_filename)) style="display:none;" @endif>
                                @if(!empty($customer->image_filename))
                                    <img src="{{ Storage::url(App\Models\Customer::DIR_PUBLIC . '/' . $customer->image_filename) }}" alt="Profile Image" class="rounded-circle img-thumbnail mb-2" width="120">
                                    <br>
                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="removeImageBtn">Remove Image</button>
                                @endif
                            </span>

                            <span id="fileContainer" @if(!empty($customer->image_filename)) style="display:none;" @endif>
                                <input type="file" id="imageInput" name="image" class="form-control">
                            </span>

                            <input type="hidden" name="isImageDelete" value="0">
                        </div>
                    </div>

                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="submit" class="btn btn-zopa">
                    <i class="fas fa-save me-1"></i> Update Profile
                </button>
                <button type="button" class="btn btn-outline-secondary" id="cancelEditBtnBottom">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // function getDistrict(stateId, selectedDistrictId = 0) {
    //     if (!stateId) return;
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
        const $profileView = $('#profileView');
        const $profileForm = $('#profileForm');

        $('#editProfileBtn').click(function() {
            $profileView.fadeOut(200, function() {
                $profileForm.fadeIn(200, function() {
                    $('html, body').animate({
                        scrollTop: $profileForm.offset().top - 100
                    }, 400);
                    $('#name').focus();
                });
            });
        });

        $('#cancelEditBtn, #cancelEditBtnBottom').click(function() {
            $profileForm.fadeOut(200, function() {
                $profileView.fadeIn(200);

                $('input[name="isImageDelete"]').val(0);
                @if(!empty($customer->image_filename))
                    $('#fileContainer').hide();
                    $('#imageContainer').show();
                @else
                    $('#fileContainer').show();
                    $('#imageContainer').hide();
                @endif
                $('#previewImage').remove();
                $('#imageInput').val('');
            });
        });

        $('#removeImageBtn').click(function() {
            $('#imageContainer').slideUp(200);
            $('#fileContainer').slideDown(200);
            $('input[name="isImageDelete"]').val(1);
        });

        $('form').on('submit', function() {
            $(this).find('button[type=submit]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');
            $(this).find('input[type=text]').each(function() {
                $(this).val($.trim($(this).val()));
            });
        });

        $('#imageInput').on('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').remove(); // Remove any old preview
                    $('<img>', {
                        id: 'previewImage',
                        src: e.target.result,
                        class: 'img-thumbnail mt-2',
                        width: 150,
                        alt: 'Preview'
                    }).insertAfter('#imageInput');
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>

@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });
</script>
@endif
@endpush
