@extends('admin.layouts.master')
@section('title') @lang('translation.Add_Role') @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
@endsection
@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Role_Management') @endslot
@slot('li_2') @lang('translation.Role_Management') @endslot
@slot('title') @lang('translation.Add_Role') @endslot
@endcomponent
<div class="row">
    <form method="POST" action="{{ isset($role)? route('admin.roles.update', encrypt($role->id)) : route('admin.roles.store')  }}">
        @csrf
        @if (isset($role))

            <input type="hidden" name="_method" value="PUT" />
        @endif
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Basic Information</h4>
                    <p class="card-title-desc">Fill all information below</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="name">Role Name</label>
                                <input id="name" name="name" type="text" class="form-control"  placeholder="Role Name" value="{{ isset($role)?$role->display_name:old('name')}}">
                                @error('name') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="control-label">Permissions</label>
                                <select class="select2 form-control select2-multiple" id="permissions" name="permissions[]" multiple="multiple" data-placeholder="Choose ...">
                                    @foreach ($permissions as $permission )
                                        <option value="{{ encrypt($permission->id) }}" {{ isset($role)&&($role->permissions->contains($permission->id)) ? 'selected' : '' }}>{{ $permission->display_name }}</option>
                                    @endforeach
                                </select>
                                @error('permissions') <p class="text-danger">{{ $message }}</p> @enderror
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
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
