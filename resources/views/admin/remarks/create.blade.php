@extends('admin.layouts.master')
@section('title') @lang('translation.Add_Remark') @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet">
@endsection
@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Inventory_Manage') @endslot
@slot('li_2') @lang('translation.Remark_Manage') @endslot
@slot('title') @if(isset($remark)) @lang('translation.Edit_Remark') @else @lang('translation.Add_Remark') @endif @endslot
@endcomponent

<div class="row">
    <form method="POST" action="{{ isset($remark) ? route('admin.remarks.update', $remark->id) : route('admin.remarks.store') }}">
        @csrf
        @if(isset($remark))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ old('name', $remark->name ?? '') }}" class="form-control" required>
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $remark->description ?? '') }}</textarea>
            @error('description') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="1" {{ old('status', $remark->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $remark->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">{{ isset($remark) ? 'Update' : 'Create' }}</button>
            <a href="{{ route('admin.remarks.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
