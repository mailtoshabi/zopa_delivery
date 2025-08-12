@extends('admin.layouts.master')
@section('title') @lang('translation.Add_Kitchen') @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
<style>
.file-input-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
}
.file-input-wrapper input[type="file"] {
    width: 100%;
    padding-right: 2.5rem; /* Make space for button */
}
.file-input-wrapper .btn-close {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    border: none;
}
</style>
@endsection
@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Account_Manage') @endslot
@slot('li_2') @lang('translation.Kitchen_Manage') @endslot
@slot('title') @if(isset($kitchen)) @lang('translation.Edit_Kitchen') @else @lang('translation.Add_Kitchen') @endif @endslot
@endcomponent
<div class="row">
    <form method="POST" action="{{ isset($kitchen)? route('admin.kitchens.update',encrypt($kitchen->id)) : route('admin.kitchens.store')  }}" enctype="multipart/form-data">
        @csrf
        @if (isset($kitchen))
            {{-- <input type="hidden" name="kitchen_id" value="{{ encrypt($kitchen->id) }}" />
            <input type="hidden" name="_method" value="PUT" /> --}}
            @method('PUT')
        @endif
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('translation.Kitchen') Details</h4>
                    <p class="card-title-desc  required">{{ isset($kitchen)? 'Edit' : "Enter" }} the Details of your @lang('translation.Kitchen'), Noted with <label></label> are mandatory.</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3 required">
                                <label for="name">@lang('translation.Name')</label>
                                <input id="name" name="name" type="text" class="form-control"  placeholder="@lang('translation.Name')" value="{{ isset($kitchen)?$kitchen->name:old('name')}}">
                                @error('name') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="display_name">@lang('translation.Display_Name')</label>
                                <input id="display_name" name="display_name" type="text" class="form-control"  placeholder="@lang('translation.Display_Name')" value="{{ isset($kitchen)?$kitchen->display_name:old('display_name')}}">
                                @error('display_name') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-3 required">
                                <label for="phone">Phone</label>
                                <input id="phone" name="phone" type="text" class="form-control"  placeholder="Phone" value="{{ isset($kitchen)?$kitchen->phone:old('phone')}}">
                                @error('phone') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="whatsapp">WhatsApp</label>
                                <input id="whatsapp" name="whatsapp" type="text" class="form-control" placeholder="WhatsApp" value="{{ isset($kitchen)?$kitchen->whatsapp:old('whatsapp')}}">
                                @error('whatsapp') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="address1">Address Line 1</label>
                                <input id="address1" name="address1" type="text" class="form-control"  placeholder="Building Number" value="{{ isset($kitchen)?$kitchen->address1:old('address1')}}">
                                {{-- @error('address1') <p class="text-danger">{{ $message }}</p> @enderror --}}
                            </div>
                            <div class="mb-3">
                                <label for="address2">Address Line 2</label>
                                <input id="address2" name="address2" type="text" class="form-control"  placeholder="Street" value="{{ isset($kitchen)?$kitchen->address2:old('address2')}}">
                                {{-- @error('address2') <p class="text-danger">{{ $message }}</p> @enderror --}}
                            </div>


                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="address3">Address Line 3</label>
                                <input id="address3" name="address3" type="text" class="form-control"  placeholder="Street" value="{{ isset($kitchen)?$kitchen->address3:old('address3')}}">
                                {{-- @error('address3') <p class="text-danger">{{ $message }}</p> @enderror --}}
                            </div>
                            <div class="mb-3 required">
                                <label for="city">City</label>
                                <input id="city" name="city" type="text" class="form-control"  placeholder="City" value="{{ isset($kitchen)?$kitchen->city:old('city')}}">
                                @error('city') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="postal_code">Postal Code</label>
                                <input id="postal_code" name="postal_code" type="text" class="form-control"  placeholder="Postal Code" value="{{ isset($kitchen)?$kitchen->postal_code:old('postal_code')}}">
                                {{-- @error('postal_code') <p class="text-danger">{{ $message }}</p> @enderror --}}
                            </div>

                            <div class="mb-3 required">
                                <label class="control-label">State</label>
                                <select id="state_id" name="state_id" class="form-control select2" onChange="getdistrict(this.value,0);">
                                    <option value="">Select State</option>
                                    @foreach ($states as $state)
                                    <option value="{{ $state->id }}" {{ $state->id==Utility::STATE_ID_KERALA ? 'selected':'' }}>{{ $state->name }}</option>
                                    @endforeach
                                </select>
                                @error('state_id') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-3 required">
                                <label class="control-label">District</label>
                                <select name="district_id" id="district-list" class="form-control select2">
                                    <option value="">Select District</option>
                                </select>
                                @error('district_id') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>

                            {{-- <div class="mb-3">
                                <label for="website">Website</label>
                                <input id="website" name="website" type="text" class="form-control"  placeholder="Website" value="{{ isset($kitchen)?$kitchen->website:old('website')}}">
                                @error('website') <p class="text-danger">{{ $message }}</p> @enderror
                            </div> --}}
                        </div>

                        <div class="col-sm-12 required">
                            <label for="postal_code">Location</label>

                            <!-- Input group for input + tooltip button -->
                            <div class="input-group mb-2">
                                <input type="text" id="autocomplete" placeholder="Enter location" class="form-control" value="{{ isset($kitchen)?$kitchen->location_name:old('location_name')}}">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="getCurrentLocation()"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="left"
                                    title="Use current location">
                                    📍
                                </button>
                            </div>

                            <!-- Hidden fields -->
                            <input type="hidden" name="latitude" id="latitude" value="{{ isset($kitchen)?$kitchen->latitude:old('latitude')}}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ isset($kitchen)?$kitchen->longitude:old('longitude')}}">
                            <input type="hidden" name="location_name" id="location_name" value="{{ isset($kitchen)?$kitchen->location_name:old('location_name')}}">

                            <!-- Map -->
                            <div id="map" style="height: 300px; width: 100%; margin-top: 10px;"></div>
                        </div>




                        {{--  --}}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Images & Documents</h4>
                    {{-- <p class="card-title-desc">Upload Image of your @lang('translation.Kitchen'), if any</p> --}}
                </div>
                <div class="card-body">

                        <div class="row">
                            <div class="col-sm-6 mb-3" id="imageWrap">
                                <label class="form-label">Main Kitchen Image</label>
                                <span id="imageContainer" @if(isset($kitchen)&&empty($kitchen->image_filename)) style="display: none" @endif>
                                    @if(isset($kitchen)&&!empty($kitchen->image_filename))
                                        <br><img src="{{ Storage::url(App\Models\Kitchen::DIR_PUBLIC . '/' . $kitchen->image_filename) }}" alt="" class="avatar-xxl rounded-circle me-2">
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    @endif
                                </span>

                                <span id="fileContainer" @if(isset($kitchen)&&!empty($kitchen->image_filename)) style="display: none" @endif>
                                    <div class="d-flex align-items-center gap-2">
                                    <input id="image" name="image" type="file" class="form-control"  placeholder="File">
                                    @if(isset($kitchen)&&!empty($kitchen->image_filename))
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    @endif
                                    </div>
                                </span>
                                <input name="isImageDelete" type="hidden" value="0">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Additional Images</label>
                                <input type="file" name="additional_images[]" class="form-control" multiple>
                                @if(!empty($meal->additional_images))
                                    <div class="mt-2 d-flex flex-wrap gap-2">
                                        @foreach(json_decode($meal->additional_images) as $image)
                                            <img src="{{ asset('storage/meals/' . $image) }}" alt="additional" class="img-thumbnail" width="80">
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="license_number">License Number</label>
                                <input id="license_number" name="license_number" type="text" class="form-control"  placeholder="License Number" value="{{ isset($kitchen)?$kitchen->license_number:old('license_number')}}">
                                @error('license_number') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-sm-6 mb-3" id="licenseWrap">
                                <label class="form-label">License Document</label>
                                <span id="license_fileImageDiv" @if(isset($kitchen)&&empty($kitchen->license_file)) style="display: none" @endif>
                                    <div class="file-input-wrapper">
                                    @if(isset($kitchen)&&!empty($kitchen->license_file))
                                    <br><a target="_blank" href="{{ Storage::url(App\Models\Kitchen::DIR_PUBLIC_LICESNSE . '/' . $kitchen->license_file) }}">
                                        View File <i class="fas fa-file"></i>
                                    </a>
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    @endif
                                    </div>
                                </span>
                                <span id="license_fileInputDiv" @if(isset($kitchen)&&!empty($kitchen->license_file)) style="display: none" @endif>
                                    <div class="file-input-wrapper">
                                    <input id="license_file" name="license_file" type="file" class="form-control"  placeholder="File">
                                    @if(isset($kitchen)&&!empty($kitchen->license_file))
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    @endif
                                    </div>
                                </span>
                                <input name="isLicenseDelete" type="hidden" value="0">
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="fssai_number">FSSAI Number</label>
                                <input id="fssai_number" name="fssai_number" type="text" class="form-control"  placeholder="FSSAI Number" value="{{ isset($kitchen)?$kitchen->fssai_number:old('fssai_number')}}">
                                @error('fssai_number') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label class="form-label">FSSAI Document</label>
                                <span id="fssai_certificateImageDiv" @if(isset($kitchen)&&empty($kitchen->fssai_certificate)) style="display: none" @endif>
                                    <div class="file-input-wrapper">
                                    @if(isset($kitchen)&&!empty($kitchen->fssai_certificate))
                                    <br><a target="_blank" href="{{ Storage::url(App\Models\Kitchen::DIR_PUBLIC_FSSAI . '/' . $kitchen->fssai_certificate) }}">
                                        View File <i class="fas fa-file"></i>
                                    </a>
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    @endif
                                    </div>
                                </span>
                                <span id="fssai_certificateInputDiv" @if(isset($kitchen)&&!empty($kitchen->fssai_certificate)) style="display: none" @endif>
                                    <div class="file-input-wrapper">
                                    <input id="fssai_certificate" name="fssai_certificate" type="file" class="form-control"  placeholder="File">
                                    @if(isset($kitchen)&&!empty($kitchen->fssai_certificate))
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    @endif
                                    </div>
                                </span>
                                <input name="isfssai_certificateDelete" type="hidden" value="0">
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label class="form-label">Other Documents</label>
                                <span id="other_documentsImageDiv" @if(isset($kitchen)&&empty($kitchen->other_documents)) style="display: none" @endif>
                                    <div class="file-input-wrapper">
                                    @if(isset($kitchen)&&!empty(json_decode($kitchen->other_documents, true)))
                                    <br>
                                    @foreach (json_decode($kitchen->other_documents) as $other_document )
                                        <a target="_blank" href="{{ Storage::url(App\Models\Kitchen::DIR_PUBLIC_OTHDOC . '/' . $other_document) }}">
                                            View File <i class="fas fa-file"></i>
                                        </a>
                                    @endforeach

                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    @endif
                                    </div>
                                </span>
                                <span id="other_documentsInputDiv" @if(isset($kitchen)&&!empty($kitchen->other_documents)) style="display: none" @endif>
                                    <div class="file-input-wrapper">
                                    <input id="other_documents" name="other_documents[  ]" type="file" class="form-control"  placeholder="File" multiple>
                                    @if(isset($kitchen)&&!empty($kitchen->other_documents))
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    @endif
                                    </div>
                                </span>
                                <input name="isother_documentsDelete" type="hidden" value="0">
                            </div>


                        </div>

                </div>

            </div> <!-- end card-->


            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Login Information</h4>
                    <p class="card-title-desc">Fill all information below</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="text" class="form-control" placeholder="Email" value="{{ isset($kitchen)?$kitchen->email:old('email')}}">
                                @error('email') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="horizontal-password-input">Password</label>
                                <input type="password" name="password" class="form-control" id="horizontal-password-input" placeholder="Enter Your Password">
                                @error('password') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save Changes</button>
                        <button type="reset" class="btn btn-secondary waves-effect waves-light">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- end row -->
@endsection
@section('script')
<script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/ecommerce-select2.init.js') }}"></script>

<script>
    $(document).ready(function() {
        @if(isset($kitchen))
            getdistrict({{ Utility::STATE_ID_KERALA }},{{ $kitchen->district_id }});
        @else
            getdistrict({{ Utility::STATE_ID_KERALA }},0);
        @endif
    });
    function getdistrict(val,d_id) {
        var formData = {'s_id' : val, 'd_id':d_id};
        $.ajax({
            type: "POST",
            url: "{{ route('admin.list.districts') }}",
            data:formData,
            success: function(data){
                $("#district-list").html(data);
                // console.log(data);
            }
        });
    }
</script>
<script>
$(function () {
    $('#imageWrap .btn-close').click(function () {
        $('#imageContainer, #fileContainer').toggle(); // Toggle both
        const isVisible = $('#imageContainer').is(':visible');
        $('[name="isImageDelete"]').val(isVisible ? 0 : 1);
    });

    $('#licenseWrap .btn-close').click(function () {
        $('#license_fileImageDiv, #license_fileInputDiv').toggle(); // Toggle both
        const isVisible = $('#license_fileImageDiv').is(':visible');
        $('[name="isLicenseDelete"]').val(isVisible ? 0 : 1);
    });
});
</script>



<script>
    $(document).ready(function() {
        // $('.select2_rent_terms').select2();
        $(document).on("click", 'a[data-toggle="add-more"]', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var $el = $($(this).attr("data-template")).clone();
            $el.removeClass("hidden");
            $el.attr("id", "");

            var count = $(this).data('count');
            count = typeof count == "undefined" ? 0 : count;
            count = count + 1;
            $(this).data('count', count);

            var addindex = $(this).data("addindex");
            if(typeof addindex == "object") {
                $.each(addindex, function(i, p) {
                    var have_child = p.have_child;
                    if(typeof(have_child)  === "undefined") {
                        $el.find(p.selector).attr(p.attr, p.value + '[' + count + ']');
                    }else {
                        $el.find(p.selector).attr(p.attr, p.value +'['+count+']'+'['+have_child+']' );
                    }
                });
            }

            var increment = $(this).data("increment");
            if(typeof increment == "object") {
                $.each(increment, function(i, p) {
                    var have_child = p.have_child;
                    if(typeof(have_child)  === "undefined") {
                        $el.find(p.selector).attr(p.attr, p.value +"-"+count);
                    }else {
                        $el.find(p.selector).attr(p.attr, p.value +"-"+count+"-"+have_child);
                    }
                });
            }

            var plugins = $(this).data("plugins");
            $.each(plugins, function(i, p) {
                if(p.plugin=='select2') {
                    //$el.find(p.selector).select2();
                }

            });

            $el.hide().appendTo($(this).attr("data-container")).fadeIn();

        });

    })
</script>

<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBlsEVjPfChYExnraJoKmt7aG7ItrPZ9TA&libraries=places&callback=initAutocomplete"
  async
  defer>
</script>
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
            geocoder.geocode({ location: position }, function (results, status) {
                if (status === 'OK' && results[0]) {
                    document.getElementById('location_name').value = results[0].formatted_address;
                    document.getElementById('autocomplete').value = results[0].formatted_address;
                }
            });
        });

        // Handle autocomplete selection
        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();
            if (!place.geometry) return;

            const location = place.geometry.location;
            map.setCenter(location);
            map.setZoom(15);
            marker.setPosition(location);

            document.getElementById('latitude').value = location.lat();
            document.getElementById('longitude').value = location.lng();
            document.getElementById('location_name').value = place.formatted_address;
        });
    }

    // google.maps.event.addDomListener(window, 'load', initAutocomplete);



    function getCurrentLocation() {
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
                    }
                });
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
</script>

@endsection
