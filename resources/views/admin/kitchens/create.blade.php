@extends('admin.layouts.master')
@section('title') @lang('translation.Add_Kitchen') @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
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
                            {{-- <div class="mb-3">
                                <label for="trade_name">@lang('translation.Office_Name')</label>
                                <input id="trade_name" name="trade_name" type="text" class="form-control"  placeholder="@lang('translation.Office_Name')" value="{{ isset($kitchen)?$kitchen->trade_name:old('trade_name')}}">
                                @error('trade_name') <p class="text-danger">{{ $message }}</p> @enderror
                            </div> --}}
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


                        {{--  --}}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Image</h4>
                    <p class="card-title-desc">Upload Image of your @lang('translation.Kitchen'), if any</p>
                </div>
                <div class="card-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <span id="imageContainer" @if(isset($kitchen)&&empty($kitchen->image_filename)) style="display: none" @endif>
                                        @if(isset($kitchen)&&!empty($kitchen->image_filename))
                                            <img src="{{ Storage::url(App\Models\Kitchen::DIR_PUBLIC . '/' . $kitchen->image_filename) }}" alt="" class="avatar-xxl rounded-circle me-2">
                                            <button type="button" class="btn-close" aria-label="Close"></button>
                                        @endif
                                    </span>

                                    <span id="fileContainer" @if(isset($kitchen)&&!empty($kitchen->image_filename)) style="display: none" @endif>
                                        <input id="image" name="image" type="file" class="form-control"  placeholder="File">
                                        @if(isset($kitchen)&&!empty($kitchen->image_filename))
                                            <button type="button" class="btn-close" aria-label="Close"></button>
                                        @endif
                                    </span>
                                    <input name="isImageDelete" type="hidden" value="0">
                                </div>
                            </div>
                        </div>

                </div>

            </div> <!-- end card-->

            {{-- <div class="card">
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
            </div> --}}

            {{-- <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Contact persons</h4>
                    <p class="card-title-desc">Add details of contact person</p>
                </div>
                <div class="card-body" id="contact_persons_container">
                    @isset($kitchen)
                        @foreach ($kitchen->contactPersons as $index => $contactPerson)
                            <div class="row close_container" id="close_container_{{ $index }}">

                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input id="contact_names-{{ $index }}" name="contact_names[{{ $index }}]" type="text" class="form-control"  placeholder="Name" value="{{ $contactPerson->name }}">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label>Phone</label>
                                        <input id="phones-{{ $index }}" name="phones[{{ $index }}]" type="text" class="form-control"  placeholder="Phone" value="{{ $contactPerson->phone }}">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input id="emails-{{ $index }}" name="emails[{{ $index }}]" type="text" class="form-control"  placeholder="Email" value="{{ $contactPerson->email }}">
                                    </div>
                                </div>
                                <a class="btn-close" data-target="#close_container_{{ $index }}"><i class="fa fa-trash"></i></a>
                            </div>
                        @endforeach

                    @endisset
                    @empty($kitchen)
                        <div class="row">


                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label for="contact_names">Name</label>
                                    <input id="contact_names-0" name="contact_names[0]" type="text" class="form-control"  placeholder="Name" value="">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>Phone</label>
                                    <input id="phones-0" name="phones[0]" type="text" class="form-control"  placeholder="Phone" value="">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input id="emails-0" name="emails[0]" type="text" class="form-control"  placeholder="Email" value="">
                                </div>
                            </div>
                        </div>
                    @endempty
                </div>
                <div class="p-4 pt-1">
                    <a href="#" data-toggle="add-more" data-template="#template_contact_persons"
                    data-close=".wb-close" data-container="#contact_persons_container"
                    data-count="{{ isset($kitchen) ? $kitchen->contactPersons->count()-1 : 0 }}"
                    data-addindex='[{"selector":".contact_names","attr":"name", "value":"contact_names"},{"selector":".phones","attr":"name", "value":"phones"},{"selector":".emails","attr":"name", "value":"emails"}]'
                    data-increment='[{"selector":".contact_names","attr":"id", "value":"contact_names"},{"selector":".phones","attr":"id", "value":"phones"},{"selector":".emails","attr":"id", "value":"emails"}]'><i
                                class="fa fa-plus-circle"></i>&nbsp;&nbsp;New Contact</a>
                </div>
            </div>


            <div class="row hidden" id="template_contact_persons">

                <div class="col-sm-4">
                    <div class="mb-3">
                        <label>Name</label>
                        <input id="" name="" type="text" class="form-control contact_names"  placeholder="Name" value="">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label>Phone</label>
                        <input id="phones-0" name="" type="text" class="form-control phones"  placeholder="Phone" value="">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="mb-3">
                        <label>Email</label>
                        <input id="" name="" type="text" class="form-control emails"  placeholder="Email" value="">
                    </div>
                </div>
            </div> --}}

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
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
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
    $(document).ready(function() {
        $('#imageContainer').find('button').click(function() {
            $('#imageContainer').hide();
            $('#fileContainer').show();
            $('input[name="isImageDelete"]').val(1);
        })

        $('#fileContainer').find('button').click(function() {
            $('#fileContainer').hide();
            $('#imageContainer').show();
            $('input[name="isImageDelete"]').val(0);
        })
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
@endsection
