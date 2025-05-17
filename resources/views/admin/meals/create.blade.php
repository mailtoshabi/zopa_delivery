@extends('admin.layouts.master')
@section('title') {{ isset($meal) ? __('Edit Meal') : __('Add Meal') }} @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
@endsection
@php
    $isEdit = isset($meal);
@endphp
@section('content')

@component('admin.dir_components.breadcrumb')
    @slot('li_1') Meal Management @endslot
    @slot('li_2') Meals @endslot
    @slot('title') {{ $isEdit ? 'Edit Meal' : 'Add Meal' }} @endslot
@endcomponent



<form action="{{ $isEdit ? route('admin.meals.update', encrypt($meal->id)) : route('admin.meals.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row">
        {{-- Meal Name --}}
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Meal Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $meal->name ?? '') }}" required>
        </div>

        {{-- Price --}}
        <div class="col-md-6 mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" name="price" class="form-control" value="{{ old('price', $meal->price ?? '') }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $meal->quantity ?? 0) }}">
        </div>

        {{-- Order --}}
        <div class="col-md-6 mb-3">
            <label for="order" class="form-label">Display Order</label>
            <input type="number" name="order" class="form-control" value="{{ old('order', $meal->order ?? 0) }}">
        </div>

        {{-- Status --}}
        {{-- <div class="col-md-6 mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="1" {{ old('status', $meal->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $meal->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div> --}}

        {{-- Ingredient Multi-Select --}}
        <div class="col-md-6 mb-3">
            <label for="ingredients" class="form-label">Ingredients</label>
            <select name="ingredient_ids[]" id="ingredients" class="form-control select2" multiple>
                <option value="">Select</option>
                @foreach ($ingredients as $key => $ingredient)
                    <option value="{{ $key }}"
                        @if(isset($meal) && $meal->ingredients->contains($key)) selected @endif>
                        {{ $ingredient }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <select name="remark_ids[]" id="remarks" class="form-control select2" multiple>
                <option value="">Select</option>
                @foreach ($remarks as $key => $remarks)
                    <option value="{{ $key }}"
                        @if(isset($meal) && $meal->remarks->contains($key)) selected @endif>
                        {{ $remarks }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Featured Image --}}
        {{-- <div class="col-md-6 mb-3">
            <label class="form-label">Main Image</label>
            @if(!empty($meal->image_filename))
                <div class="mb-2">
                    <img src="{{ asset('storage/meals/' . $meal->image_filename) }}" alt="image" class="img-thumbnail" width="100">
                </div>
            @endif
            <input type="file" name="image" class="form-control">
        </div> --}}

        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Image</h4>
                <p class="card-title-desc">Upload Image of your @lang('translation.meal'), if any</p>
            </div>
            <div class="card-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <span id="imageContainer" @if(isset($meal)&&empty($meal->image_filename)) style="display: none" @endif>
                                    @if(isset($meal)&&!empty($meal->image_filename))
                                        <img src="{{ Storage::url(App\Models\Meal::DIR_PUBLIC. '/' . $meal->image_filename) }}" alt="" class="avatar-xxl rounded-circle me-2">
                                        <button type="button" class="btn-close" aria-label="Close"></button>
                                    @endif
                                </span>

                                <span id="fileContainer" @if(isset($meal)&&!empty($meal->image_filename)) style="display: none" @endif>
                                    <input id="image" name="image" type="file" class="form-control"  placeholder="File">
                                    @if(isset($meal)&&!empty($meal->image_filename))
                                        <button type="button" class="btn-close" aria-label="Close"></button>
                                    @endif
                                </span>
                                <input name="isImageDelete" type="hidden" value="0">
                            </div>
                        </div>
                    </div>

            </div>

        </div> <!-- end card-->

        {{-- Additional Images --}}
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
    </div>

    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Create' }} Meal</button>
</form>

@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#imageContainer').find('button').click(function() {
            $('#imageContainer').hide();
            $('#fileContainer').show();
            $('input[name="isImageDelete"]').val(1);
        });

        $('#fileContainer').find('button').click(function() {
            $('#fileContainer').hide();
            $('#imageContainer').show();
            $('input[name="isImageDelete"]').val(0);
        });
    });
</script>
@endsection
