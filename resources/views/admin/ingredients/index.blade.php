@extends('admin.layouts.master')
@section('title') @lang('translation.Ingredients') @endsection
@section('css')
<link href="{{ URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('admin.dir_components.breadcrumb')
    @slot('li_1') @lang('translation.Meal_Manage') @endslot
    @slot('li_2') @lang('translation.Ingredients') @endslot
    @slot('title') @lang('translation.Ingredient_List') @endslot
@endcomponent

@if(session()->has('success'))
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i><strong>Success</strong> - {{ session()->get('success') }}
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-body">
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" role="tabpanel">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5 class="card-title">@lang('translation.Ingredient_List') <span class="text-muted fw-normal ms-2">({{ $ingredients->total() }})</span></h5>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('admin.ingredients.create') }}" class="btn btn-primary btn-sm"><i class="mdi mdi-plus"></i> Add Ingredient</a>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table align-middle dt-responsive table-check nowrap" style="border-collapse: collapse; border-spacing: 0 8px; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th style="width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ingredients as $ingredient)
                                        <tr>
                                            <td>{{ $ingredient->name }}</td>
                                            <td>{{ Str::limit($ingredient->description, 40) }}</td>
                                            <td>
                                                <a href="{{ route('admin.ingredients.changeStatus', encrypt($ingredient->id)) }}" class="badge bg-{{ $ingredient->status ? 'success' : 'secondary' }}">
                                                    {{ $ingredient->status ? 'Active' : 'Inactive' }}
                                                </a>
                                            </td>
                                            <td>{{ $ingredient->created_at->format('d M Y') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bx bx-dots-horizontal-rounded"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.ingredients.edit', encrypt($ingredient->id)) }}">
                                                                <i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="dropdown-item" data-plugin="delete-data" data-target-form="#form_delete_{{ $loop->iteration }}">
                                                                <i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete
                                                            </a>
                                                            <form id="form_delete_{{ $loop->iteration }}" method="POST" action="{{ route('admin.ingredients.destroy', $ingredient->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="pagination justify-content-center mt-3">
                                {{ $ingredients->links() }}
                            </div>
                        </div> <!-- end table responsive -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/datatables.net/datatables.net.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-responsive/datatables.net-responsive.min.js') }}"></script>

<script src="{{ URL::asset('assets/js/pages/datatable-pages.init.js') }}"></script>
@endsection
