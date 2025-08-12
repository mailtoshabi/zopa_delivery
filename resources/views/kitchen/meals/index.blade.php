@extends('kitchen.layouts.master')
@section('title') @lang('translation.Meals') @endsection
@section('css')
<link href="{{ URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
@component('kitchen.dir_components.breadcrumb')
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
                                {{-- <a href="{{ route('kitchen.meals.create') }}" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> Add A Meal
                                </a> --}}
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table align-middle dt-responsive table-check nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
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
                                            @if($meal->image_filename)
                                                <img src="{{ Storage::url('meals/' . $meal->image_filename) }}" alt="" class="avatar-sm rounded-circle me-2">
                                            @else
                                            <div class="avatar-sm d-inline-block align-middle me-2">
                                                <div class="avatar-title bg-soft-primary text-primary font-size-20 m-0 rounded-circle">
                                                    <i class="bx bxs-user-circle"></i>
                                                </div>
                                            </div>
                                            @endif
                                            <a href="">
                                                {{ $meal->name }}<br>
                                            </a>
                                        </td>
                                        <td>
                                            @isset($meal->mess_category)
                                                {{ $meal->mess_category->name }}
                                            @endisset
                                            @isset($meal->walletGroup)
                                                <br><small>Wallet : {{ $meal->walletGroup->name }}</small>
                                            @endisset
                                        </td>
                                        <td>₹{{ $meal->kitchens->first()?->pivot?->price ?? $meal->price }}</td>
                                        <td>
                                            @foreach($meal->ingredients ?? [] as $ingredient)
                                                <span class="badge bg-light text-dark">{{ $ingredient->name }}</span>
                                            @endforeach <br>
                                            @foreach($meal->remarks ?? [] as $remark)
                                                <span class="badge bg-dark text-light">{{ $remark->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @php
                                                $pivotStatus = $meal->kitchens->first()?->pivot?->status;
                                                $statusToShow = $pivotStatus ?? $meal->status;
                                            @endphp

                                            @if($statusToShow)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $meal->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item"
                                                        href="javascript:void(0);"
                                                        onclick="openManageMealModal({{ $meal->id }}, '{{ $meal->kitchens->first()?->pivot?->price ?? $meal->price }}', {{ $meal->status }})">
                                                            <i class="mdi mdi-pencil font-size-16 text-primary me-1"></i> Manage
                                                        </a>
                                                    </li>
                                                    {{-- <li>
                                                        <a class="dropdown-item" href="{{ route('kitchen.meals.changeStatus', encrypt($meal->id)) }}">
                                                            {!! $meal->status ? '<i class="fas fa-power-off font-size-16 text-danger me-1"></i> Unpublish' : '<i class="fas fa-circle-notch font-size-16 text-primary me-1"></i> Publish' !!}
                                                        </a>
                                                    </li> --}}
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

<!-- Manage Meal Modal -->
<div class="modal fade" id="manageMealModal" tabindex="-1" aria-labelledby="manageMealModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="manageMealForm" method="POST" action="{{ route('kitchen.meals.update') }}">
            @csrf
            <input type="hidden" name="meal_id" id="modal_meal_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manageMealModalLabel">Manage Meal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_price" class="form-label">Price</label>
                        <input type="text" name="price" id="modal_price" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal_status" class="form-label">Status</label>
                        <select name="status" id="modal_status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/datatables.net/datatables.net.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-responsive/datatables.net-responsive.min.js') }}"></script>

<script src="{{ URL::asset('assets/js/pages/datatable-pages.init.js') }}"></script>

<script>
function openManageMealModal(mealId, price, status) {
    document.getElementById('modal_meal_id').value = mealId;
    document.getElementById('modal_price').value = price;
    document.getElementById('modal_status').value = status;
    new bootstrap.Modal(document.getElementById('manageMealModal')).show();
}
</script>
@endsection
