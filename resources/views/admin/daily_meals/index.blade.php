@extends('admin.layouts.master')
@section('title') @lang('translation.Daily_Meals') @endsection

@section('css')
<link href="{{ URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Order_Manage') @endslot
@slot('li_2') @lang('translation.Orders') @endslot
@slot('title')
    @if($mealtype ==1)
        @lang('translation.Daily_orders')
    @elseif ($mealtype ==2)
        @lang('translation.Previous_orders')
    @else
        @lang('translation.Extra_orders')
    @endif
@endslot
@endcomponent

@if(session()->has('success'))
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i><strong>Success</strong> - {{ session()->get('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-alert-circle-outline me-3 align-middle text-danger"></i><strong>Error</strong> - {{ $errors->first() }}
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-body">
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5 class="card-title">@lang('translation.Meal_List') <span class="text-muted fw-normal ms-2">({{ $dailyMeals->total() }})</span></h5>
                                </div>
                            </div>
                            @if($mealtype ==1)
                                <div class="col-md-6 text-start">
                                    <div class="d-inline-block me-2">
                                        <form action="{{ route('admin.daily_meals.mark.all.delivered') }}" method="POST" onsubmit="return confirm('Are you sure to mark all as delivered?')">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="mdi mdi-check-all"></i> Mark Delivered
                                            </button>
                                        </form>
                                    </div>
                                    <div class="d-inline-block me-2">
                                        <form action="{{ route('admin.daily_meals.undo.delivered') }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure to undo all delivered meals for today?')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                                <i class="mdi mdi-undo"></i> Undo Delivery
                                            </button>
                                        </form>
                                    </div>
                                    <div class="d-inline-block">
                                        <form action="{{ route('admin.daily_meals.generate') }}" method="POST" onsubmit="return confirm('Are you sure to generate daily meals?')">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="mdi mdi-sync"></i> Generate From Wallet
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            @endisset
                        </div>

                        <div class="table-responsive mb-4">
                            <form method="GET" class="row g-3 mb-3">
                                <div class="col-auto">
                                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                    <a href="{{ route('admin.daily_meals.extra') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                                </div>
                            </form>
                            <table class="table align-middle dt-responsive nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th>Quantity</th>
                                        <th>Addons</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dailyMeals as $meal)
                                        <tr>
                                            <td>{{ $meal->customer->name ?? 'N/A' }}</td>
                                            <td>{{ $meal->customer->phone ?? '-' }}</td>
                                            <td>{{ $meal->quantity }}</td>

                                            {{-- Addons column --}}
                                            <td>
                                                @php
                                                    $addons = $addonsByMeal[$meal->id] ?? collect();
                                                @endphp

                                                @if($addons->isNotEmpty())
                                                    <ul class="mb-0 ps-3">
                                                        @foreach($addons as $addon)
                                                            <li>
                                                                {{ $addon->addon->name ?? '-' }} ({{ $addon->quantity }})
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge bg-{{ $meal->is_auto ? 'secondary' : 'primary' }}">
                                                    {{ $meal->is_auto ? 'Auto Generated' : 'Requested' }}
                                                </span>
                                                <span class="badge bg-{{ $meal->status ? 'success' : 'danger' }}">
                                                    {{ $meal->status ? 'Active' : 'Cancelled' }}
                                                </span>
                                                @if($meal->date->isToday())
                                                    <span class="badge bg-{{ $meal->is_delivered ? 'primary' : 'warning' }}">
                                                        {{ $meal->is_delivered ? 'Delivered' : 'Sheduled' }}
                                                    </span>
                                                @else
                                                    @if($meal->status && $meal->date->lte(\Carbon\Carbon::today()))
                                                        <span class="badge bg-{{ $meal->is_delivered ? 'warning' : 'danger' }}"
                                                            @if(!$meal->is_delivered)
                                                            data-bs-toggle="tooltip"
                                                            @endif
                                                            data-bs-placement="top" title="{{ $meal->reason ?? 'No reason provided' }}">
                                                            {{ $meal->is_delivered ? 'Delivered' : 'Not Delivered' }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>

                                            <td>{{ $meal->date->format('d M Y') }}</td>

                                            <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bx bx-dots-horizontal-rounded"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <!-- Undelivered Reason Button -->
                                                            @if($meal->status && $meal->date->lte(\Carbon\Carbon::today()))
                                                                @if($meal->is_delivered)
                                                                    <!-- Trigger Modal -->
                                                                    <li>
                                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#undeliverModal{{ $meal->id }}">
                                                                            <i class="fas fa-power-off font-size-16 text-danger me-1"></i> Mark Un Delivered
                                                                        </button>
                                                                    </li>
                                                                @else
                                                                    <!-- Deliver directly via form -->
                                                                    <li>
                                                                        <form action="{{ route('admin.daily_meals.changeDelivery', encrypt($meal->id)) }}" method="POST" onsubmit="return confirm('Mark as Delivered?')">
                                                                            @csrf
                                                                            <button type="submit" class="dropdown-item">
                                                                                <i class="fas fa-circle-notch font-size-16 text-primary me-1"></i> Mark Delivered
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                @endif
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No daily meals found.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                            </table>

                            <div class="pagination justify-content-center mt-3">
                                {{ $dailyMeals->appends(request()->query())->links() }}
                            </div>
                        </div> <!-- end table-responsive -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($dailyMeals as $meal)
    @if ($meal->is_delivered)
    <!-- Modal -->
    <div class="modal fade" id="undeliverModal{{ $meal->id }}" tabindex="-1" aria-labelledby="undeliverModalLabel{{ $meal->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.daily_meals.changeDelivery', encrypt($meal->id)) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="undeliverModalLabel{{ $meal->id }}">Reason for Undelivered</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Enter reason for undelivering this meal"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Mark Un Delivered</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
@endforeach
@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/datatables.net/datatables.net.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-responsive/datatables.net-responsive.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/datatable-pages.init.js') }}"></script>
@endsection
