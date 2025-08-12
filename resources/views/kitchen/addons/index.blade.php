@extends('kitchen.layouts.master')
@section('title') @lang('translation.Addons') @endsection
@section('css')
<link href="{{ URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
@component('kitchen.dir_components.breadcrumb')
    @slot('li_1') @lang('translation.Meal_Manage') @endslot
    @slot('li_2') @lang('translation.Addons') @endslot
    @slot('title') @lang('translation.Addon_List') @endslot
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
                                <h5 class="card-title">@lang('translation.Addon_List') <span class="text-muted fw-normal ms-2">({{ $addons->total() }})</span></h5>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table align-middle dt-responsive table-check nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th style="width: 80px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($addons as $addon)
                                    <tr>
                                        <td>
                                            @if($addon->image_filename)
                                                {{-- <img src="{{ asset('storage/addons/' . $addon->image_filename) }}" width="50" class="img-thumbnail"> --}}
                                                <img src="{{ Storage::url('addons/' . $addon->image_filename) }}" alt="" class="avatar-sm rounded-circle me-2">
                                            @else
                                            <div class="avatar-sm d-inline-block align-middle me-2">
                                                <div class="avatar-title bg-soft-primary text-primary font-size-20 m-0 rounded-circle">
                                                    <i class="bx bxs-user-circle"></i>
                                                </div>
                                            </div>
                                            @endif
                                            <a href="">
                                                {{ $addon->name }}
                                            </a>
                                        </td>
                                        <td>₹{{ $addon->kitchens->first()?->pivot?->price ?? $addon->price }}</td>
                                        <td>
                                            {{ $addon->description }}
                                        </td>

                                        <td>
                                            @php
                                                $pivotStatus = $addon->kitchens->first()?->pivot?->status;
                                                $statusToShow = $pivotStatus ?? $addon->status;
                                            @endphp

                                            @if($statusToShow)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td>{{ $addon->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item"
                                                        href="javascript:void(0);"
                                                        onclick="openManageAddonModal(
                                                            {{ $addon->id }},
                                                            '{{ $addon->kitchens->first()?->pivot?->price ?? $addon->price }}',
                                                            {{ $addon->kitchens->first()?->pivot?->status ?? $addon->status }}
                                                        )">
                                                            <i class="mdi mdi-pencil font-size-16 text-primary me-1"></i> Manage
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination justify-content-center mt-3">
                                {{ $addons->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Addon Modal -->
<div class="modal fade" id="manageAddonModal" tabindex="-1" aria-labelledby="manageAddonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="manageAddonForm" method="POST" action="{{ route('kitchen.addons.update') }}">
            @csrf
            <input type="hidden" name="addon_id" id="addon_modal_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manageAddonModalLabel">Manage Addon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="addon_modal_price" class="form-label">Price</label>
                        <input type="text" name="price" id="addon_modal_price" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="addon_modal_status" class="form-label">Status</label>
                        <select name="status" id="addon_modal_status" class="form-control">
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
function openManageAddonModal(addonId, price, status) {
    document.getElementById('addon_modal_id').value = addonId;
    document.getElementById('addon_modal_price').value = price;
    document.getElementById('addon_modal_status').value = status;
    new bootstrap.Modal(document.getElementById('manageAddonModal')).show();
}
</script>

@endsection
