@extends('admin.layouts.master')
@section('title') @lang('translation.Meals') @endsection
@section('css')
<link href="{{ URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
@component('admin.dir_components.breadcrumb')
    @slot('li_1') @lang('translation.Meal_Manage') @endslot
    @slot('li_2') @lang('translation.Meals') @endslot
    @slot('title') @lang('translation.Meal_List') @endslot
@endcomponent

@if(session()->has('success'))
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i>
    <strong>Success</strong> - {{ session()->get('success') }}
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-body">
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" role="tabpanel">
                        <div class="row align-items-center mb-3">
                            <div class="col-md-6">
                                <h5 class="card-title">@lang('translation.Meal_List') <span class="text-muted fw-normal ms-2">({{ $meals->total() }})</span></h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('admin.meals.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> Add A Meal
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table align-middle dt-responsive table-check nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Image</th>
                                        <th>Ingredients</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th style="width: 80px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($meals as $meal)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.meals.edit', encrypt($meal->id)) }}">
                                                {{ $meal->name }}<br>
                                                @isset($meal->category)
                                                    <small>Category: {{ $meal->category->name }}</small><br>
                                                @endisset
                                                @isset($meal->mess_category)
                                                    <small>Mess Category: {{ $meal->mess_category->name }}</small>
                                                @endisset
                                                @isset($meal->walletGroup)
                                                    <small>Wallet Group: {{ $meal->walletGroup->name }}</small>
                                                @endisset
                                            </a>
                                        </td>
                                        <td>₹{{ $meal->price }}</td>
                                        <td>
                                            @if($meal->image_filename)
                                                {{-- <img src="{{ asset('storage/meals/' . $meal->image_filename) }}" width="50" class="img-thumbnail"> --}}
                                                <img src="{{ Storage::url('meals/' . $meal->image_filename) }}" alt="" class="avatar-sm rounded-circle me-2">
                                            @else
                                            <div class="avatar-sm d-inline-block align-middle me-2">
                                                <div class="avatar-title bg-soft-primary text-primary font-size-20 m-0 rounded-circle">
                                                    <i class="bx bxs-user-circle"></i>
                                                </div>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach($meal->ingredients ?? [] as $ingredient)
                                                <span class="badge bg-light text-dark">{{ $ingredient->name }}</span>
                                            @endforeach <br>
                                            @foreach($meal->remarks ?? [] as $remark)
                                                <span class="badge bg-dark text-light">{{ $remark->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            {!! $meal->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' !!}
                                        </td>
                                        <td>{{ $meal->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="{{ route('admin.meals.edit', encrypt($meal->id)) }}"><i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit</a></li>
                                                    <li><a href="#" class="dropdown-item" data-plugin="delete-data" data-target-form="#form_delete_{{ $loop->iteration }}"><i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete</a></li>
                                                    <form id="form_delete_{{ $loop->iteration }}" method="POST" action="{{ route('admin.meals.destroy', encrypt($meal->id)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <li>
                                                        @php
                                                            $stat_msg = $meal->status ? 'Unpublish':'Publish';
                                                            // return confirm('Are you sure to '. $stat_msg . '?')
                                                        @endphp
                                                        <a onclick="return confirm('Are you sure to {{ $stat_msg }} the Food?')" class="dropdown-item" href="{{ route('admin.meals.changeStatus', encrypt($meal->id)) }}">
                                                            {!! $meal->status ? '<i class="fas fa-power-off font-size-16 text-danger me-1"></i> Unpublish' : '<i class="fas fa-circle-notch font-size-16 text-primary me-1"></i> Publish' !!}
                                                        </a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="{{ route('admin.meals.show', encrypt($meal->id)) }}"><i class="fa fa-eye font-size-16 text-success me-1"></i> Details</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination justify-content-center mt-3">
                                {{ $meals->links() }}
                            </div>
                        </div>

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
