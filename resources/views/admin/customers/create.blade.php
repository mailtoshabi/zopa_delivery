@extends('admin.layouts.master')
@section('title') @lang('translation.Add_Customer') @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
@endsection
@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Account_Manage') @endslot
@slot('li_2') @lang('translation.Customer_Manage') @endslot
@slot('title') @if(isset($customer)) @lang('translation.Edit_Customer') @else @lang('translation.Add_Customer') @endif @endslot
@endcomponent
<div class="row">
    <form method="POST" action="{{ isset($customer)? route('admin.customers.update',encrypt($customer->id)) : route('admin.customers.store')  }}" enctype="multipart/form-data">
        @csrf
        @if (isset($customer))
            @method('PUT')
        @endif
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('translation.Customer') Details</h4>
                    <p class="card-title-desc  required">{{ isset($customer)? 'Edit' : "Enter" }} the Details of your @lang('translation.Customer'), Noted with <label></label> are mandatory.</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3 required">
                                <label for="name">@lang('translation.Name')</label>
                                <input id="name" name="name" type="text" class="form-control"  placeholder="@lang('translation.Name')" value="{{ isset($customer)?$customer->name:old('name')}}">
                                @error('name') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-3 required">
                                <label for="office_name">Shop/Office Name</label>
                                <input id="office_name" name="office_name" type="text" class="form-control"  placeholder="Shop/Office Name" value="{{ isset($customer)?$customer->office_name:old('office_name')}}">
                                @error('office_name') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="designation">Job Designation</label>
                                <input id="designation" name="designation" type="text" class="form-control"  placeholder="Job Designation" value="{{ isset($customer)?$customer->designation:old('designation')}}">
                            </div>
                            <div class="mb-3">
                                <label for="whatsapp">Whatsapp</label>
                                <input id="whatsapp" name="whatsapp" type="text" class="form-control"  placeholder="Phone" value="{{ isset($customer)?$customer->whatsapp:old('whatsapp')}}">
                                @error('whatsapp') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-3 required">
                                <label for="city">Shop/Office Location</label>
                                <input id="city" name="city" type="text" class="form-control"  placeholder="Shop/Office Location" value="{{ isset($customer)?$customer->city:old('city')}}">
                                @error('city') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="landmark">Landmark</label>
                                <input id="landmark" name="landmark" type="text" class="form-control"  placeholder="Landmark" value="{{ isset($customer)?$customer->landmark:old('landmark')}}">
                            </div>
                        </div>

                        <div class="col-sm-6">


                            <div class="mb-3 required">
                                <label for="postal_code">Postal Code</label>
                                <input id="postal_code" name="postal_code" type="text" class="form-control"  placeholder="Postal Code" value="{{ isset($customer)?$customer->postal_code:old('postal_code')}}">
                                @error('postal_code') <p class="text-danger">{{ $message }}</p> @enderror
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

                            <div class="mb-3 required">
                                <label for="kitchen_id">Zopa Kitchen</label>
                                <select id="kitchen_id" name="kitchen_id" class="form-control select2">
                                    <option value="">Select a Kitchen</option>
                                    @foreach ($kitchens as $kitchen )
                                        <option value="{{ encrypt($kitchen->id) }}" {{ isset($customer)&&($customer->kitchen_id==$kitchen->id)?'selected':''}}>{{ $kitchen->display_name }}</option>
                                    @endforeach
                                </select>
                                @error('kitchen_id') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-3 required">
                                <label for="customer_type">Customer Type</label>
                                <select id="customer_type" name="customer_type" class="form-control select2">
                                    <option value="">Select Type</option>
                                    <option value="{{ Utility::CUSTOMER_TYPE_IND }}" {{ isset($customer)&&($customer->customer_type==Utility::CUSTOMER_TYPE_IND)?'selected':'' }}>Individual</option>
                                    <option value="{{ Utility::CUSTOMER_TYPE_INST }}" {{ isset($customer)&&($customer->customer_type==Utility::CUSTOMER_TYPE_INST)?'selected':'' }}>Institution</option>
                                </select>
                                @error('customer_type') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Image</h4>
                    <p class="card-title-desc">Upload Image of your @lang('translation.Customer'), if any</p>
                </div>
                <div class="card-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">

                                    <span id="imageContainer" @if(isset($customer)&&empty($customer->image_filename)) style="display: none" @endif>
                                        @if(isset($customer)&&!empty($customer->image_filename))
                                            <img src="{{ Storage::url(App\Models\Customer::DIR_PUBLIC . '/' . $customer->image_filename) }}" alt="" class="avatar-xxl rounded-circle me-2">
                                            <button type="button" class="btn-close" aria-label="Close">x</button>
                                        @endif
                                    </span>

                                    <span id="fileContainer" @if(isset($customer)&&!empty($customer->image_filename)) style="display: none" @endif>
                                        <div class="d-flex align-items-center gap-2">
                                        <input id="image" name="image" type="file" class="form-control"  placeholder="File">
                                        @if(isset($customer)&&!empty($customer->image_filename))
                                            <button type="button" class="btn-close" aria-label="Close">x</button>
                                        @endif
                                        </div>
                                    </span>
                                    <input name="isImageDelete" type="hidden" value="0">
                                </div>
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
                            <div class="mb-3 required">
                                <label for="phone">Phone</label>
                                <input id="phone" name="phone" type="text" class="form-control" placeholder="Phone" value="{{ isset($customer)?$customer->phone:old('phone')}}">
                                @error('phone') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3 {{ isset($customer)? '': 'required' }}">
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

        @if(isset($customer))
            getdistrict({{ Utility::STATE_ID_KERALA }},{{ $customer->district_id }});
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
        $('#imageContainer').find('button').click(function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to delete?')) return;
            $('#imageContainer').hide();
            $('#fileContainer').show();
            $('input[name="isImageDelete"]').val(1);
        })

        $('#fileContainer').find('button').click(function(e) {
            e.preventDefault();
            $('#fileContainer').hide();
            $('#imageContainer').show();
            $('input[name="isImageDelete"]').val(0);
        })
    });
</script>

<script>
    $(document).ready(function() {
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
