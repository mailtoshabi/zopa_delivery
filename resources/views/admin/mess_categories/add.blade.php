@extends('admin.layouts.master')
@section('title')  @if(isset($mess_category)) @lang('translation.Edit_MessCategory') @else @lang('translation.Add_MessCategory') @endif @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
@endsection
@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Catalogue_Manage') @endslot
@slot('li_2') @lang('translation.MessCategory_Manage') @endslot
@slot('title') @if(isset($mess_category)) @lang('translation.Edit_MessCategory') @else @lang('translation.Add_MessCategory') @endif @endslot
@endcomponent
<div class="row">
    <form method="POST" action="{{ isset($mess_category)? route('admin.mess_categories.update') : route('admin.mess_categories.store')  }}" enctype="multipart/form-data">
        @csrf
        @if (isset($mess_category))
            <input type="hidden" name="mess_category_id" value="{{ encrypt($mess_category->id) }}" />
            <input type="hidden" name="_method" value="PUT" />
        @endif

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Mess Category Details</h4>
                    <p class="card-title-desc">{{ isset($mess_category)? 'Edit' : "Enter" }} the Details of your Mess Category</p>
                </div>
                <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input id="name" name="name" type="text" class="form-control"  placeholder="Mess Category Name" value="{{ isset($mess_category)?$mess_category->name:old('name')}}">
                                    @error('name') <p class="text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="display_order">Display Order</label>
                                    <input type="number" name="display_order" class="form-control" placeholder="Display Order" value="{{ isset($mess_category)?$mess_category->display_order:old('display_order')}}" >
                                    @error('display_order') <p class="text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="control-label">Image</label>
                                    <span id="imageContainer" @if(isset($mess_category)&&empty($mess_category->image)) style="display: none" @endif>
                                        @if(isset($mess_category)&&!empty($mess_category->image))
                                            <img src="{{ URL::asset(App\Models\MessCategory::DIR_STORAGE . $mess_category->image) }}" alt="" class="avatar-xxl rounded-circle me-2">
                                            <button type="button" class="btn-close" aria-label="Close">x</button>
                                        @endif
                                    </span>

                                    <span id="fileContainer" @if(isset($mess_category)&&!empty($mess_category->image)) style="display: none" @endif>
                                        <div class="d-flex align-items-center gap-2">
                                        <input id="image" name="image" type="file" class="form-control"  placeholder="File">
                                        @if(isset($mess_category)&&!empty($mess_category->image))
                                            <button type="button" class="btn-close" aria-label="Close">x</button>
                                        @endif
                                        </div>
                                    </span>
                                    <input name="isImageDelete" type="hidden" value="0">
                                </div>
                            </div>

                        </div>
                </div>
            </div>

            {{-- <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Meta Data</h4>
                    <p class="card-title-desc">Fill all information below</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="meta_title">Meta title</label>
                                <input id="meta_title" name="meta_title" type="text" class="form-control" placeholder="Meta Title" value="{{ isset($mess_category)?$mess_category->meta_title:old('meta_title')}}">
                            </div>
                            <div class="mb-3">
                                <label for="meta_keywords">Meta Keywords</label>
                                <input id="meta_keywords" name="meta_keywords" type="text" class="form-control" placeholder="Meta Keywords" value="{{ isset($mess_category)?$mess_category->meta_keywords:old('meta_keywords')}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="meta_description">Meta Description</label>
                                <textarea class="form-control" id="meta_description" rows="5" name="meta_description" placeholder="Meta Description">{{ isset($mess_category)?$mess_category->meta_description:old('meta_description')}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">{{ isset($mess_category) ? 'Update' : 'Save' }}</button>
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
@endsection
