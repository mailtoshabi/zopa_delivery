@extends('admin.layouts.master')
@section('title')  @if(isset($wallet_group)) @lang('translation.Edit_Wallet_Group') @else @lang('translation.Add_Wallet_Group') @endif @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
@endsection
@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Catalogue_Manage') @endslot
@slot('li_2') @lang('translation.Wallet_Group_Manage') @endslot
@slot('title') @if(isset($wallet_group)) @lang('translation.Edit_Wallet_Group') @else @lang('translation.Add_Wallet_Group') @endif @endslot
@endcomponent
<div class="row">
    <form method="POST" action="{{ isset($wallet_group)? route('admin.wallet_groups.update') : route('admin.wallet_groups.store')  }}" enctype="multipart/form-data">
        @csrf
        @if (isset($wallet_group))
            <input type="hidden" name="wallet_group_id" value="{{ encrypt($wallet_group->id) }}" />
            <input type="hidden" name="_method" value="PUT" />
        @endif

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Wallet_Group Details</h4>
                    <p class="card-title-desc">{{ isset($wallet_group)? 'Edit' : "Enter" }} the Details of your Wallet_Group</p>
                </div>
                <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input id="name" name="name" type="text" class="form-control"  placeholder="Name" value="{{ isset($wallet_group)?$wallet_group->name:old('name')}}">
                                    @error('name') <p class="text-danger">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="display_name">Display Name</label>
                                    <input id="display_name" name="display_name" type="text" class="form-control"  placeholder="Display Name" value="{{ isset($wallet_group)?$wallet_group->display_name:old('display_name')}}">
                                    @error('display_name') <p class="text-danger">{{ $message }}</p> @enderror
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
                                <input id="meta_title" name="meta_title" type="text" class="form-control" placeholder="Meta Title" value="{{ isset($wallet_group)?$wallet_group->meta_title:old('meta_title')}}">
                            </div>
                            <div class="mb-3">
                                <label for="meta_keywords">Meta Keywords</label>
                                <input id="meta_keywords" name="meta_keywords" type="text" class="form-control" placeholder="Meta Keywords" value="{{ isset($wallet_group)?$wallet_group->meta_keywords:old('meta_keywords')}}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="meta_description">Meta Description</label>
                                <textarea class="form-control" id="meta_description" rows="5" name="meta_description" placeholder="Meta Description">{{ isset($wallet_group)?$wallet_group->meta_description:old('meta_description')}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">{{ isset($wallet_group) ? 'Update' : 'Save' }}</button>
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
