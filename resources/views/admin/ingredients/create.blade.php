@extends('admin.layouts.master')
@section('title') @lang('translation.Add_Ingredient') @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet">
@endsection
@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Inventory_Manage') @endslot
@slot('li_2') @lang('translation.Ingredient_Manage') @endslot
@slot('title') @if(isset($ingredient)) @lang('translation.Edit_Ingredient') @else @lang('translation.Add_Ingredient') @endif @endslot
@endcomponent

<div class="row">
    <form method="POST" action="{{ isset($ingredient)? route('admin.ingredients.update', encrypt($ingredient->id)) : route('admin.ingredients.store') }}">
        @csrf
        @if (isset($ingredient))
            @method('PUT')
        @endif

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('translation.Ingredient') Details</h4>
                    <p class="card-title-desc required">{{ isset($ingredient)? 'Edit' : "Enter" }} the Details of the @lang('translation.Ingredient'), fields marked with <label></label> are mandatory.</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3 required">
                                <label for="name">@lang('translation.Name')</label>
                                <input id="name" name="name" type="text" class="form-control" placeholder="@lang('translation.Name')" value="{{ isset($ingredient)? $ingredient->name : old('name') }}">
                                @error('name') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description">@lang('translation.Description')</label>
                                <textarea id="description" name="description" class="form-control" placeholder="@lang('translation.Description')">{{ isset($ingredient)? $ingredient->description : old('description') }}</textarea>
                                @error('description') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="status">@lang('translation.Status')</label>
                                <select name="status" id="i_status" class="form-control select2">
                                    <option value="1" {{ (isset($ingredient) && $ingredient->status == 1) ? 'selected' : '' }}>@lang('translation.Active')</option>
                                    <option value="0" {{ (isset($ingredient) && $ingredient->status == 0) ? 'selected' : '' }}>@lang('translation.Inactive')</option>
                                </select>
                                @error('status') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
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
@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
