@extends('admin.layouts.master')
@section('title') @lang('translation.Daily_orders') @endsection
@section('css')
<link href="{{ URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Order_Manage') @endslot
@slot('li_2') @lang('translation.Orders') @endslot
@slot('title') @lang('translation.Purchases') @endslot
@endcomponent
@if(session()->has('success'))
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i><strong>Success</strong> - {{ session()->get('success') }}
</div>
@endif
<div class="row">
    <div class="col-lg-12">
    <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link @if($is_active==Utility::ITEM_INACTIVE) active @endif" @if($is_active==Utility::ITEM_INACTIVE)aria-current="page"@endif href="{{ route('admin.orders.index','status='.encrypt(Utility::ITEM_INACTIVE)) }}">Pending to add Wallet <span class="badge rounded-pill bg-soft-danger text-danger float-end">{{ $count_new }}</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if($is_active==Utility::ITEM_ACTIVE) active @endif" @if($is_active==Utility::ITEM_ACTIVE)aria-current="page"@endif href="{{ route('admin.orders.index','status='.encrypt(Utility::ITEM_ACTIVE)) }}">Added to Wallet</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger @if($is_active==Utility::STATUS_NOTPAID) active @endif" @if($is_active==Utility::STATUS_NOTPAID)aria-current="page"@endif href="{{ route('admin.orders.index','status='.encrypt(Utility::STATUS_NOTPAID)) }}">UnPaid <span class="badge rounded-pill bg-soft-danger text-danger float-end">{{ $count_not_paid }}</span></a>
          </li>
      </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-body">
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane customerdetailsTab active" role="tabpanel">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="mb-3">
                                <h5 class="card-title">@lang('translation.Orders') <span class="text-muted fw-normal ms-2">({{ $customer_orders->total() }})</span></h5>
                                </div>
                            </div>

                            <div class="table-responsive mb-4">
                                <table class="table align-middle dt-responsive table-check nowrap" style="border-collapse: collapse; border-spacing: 0 8px; width: 100%;">
                                    <thead>
                                    <tr>
                                        <th scope="col">Invoice</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Grand Total</th>
                                        <th scope="col">Payment</th>
                                        <th scope="col">Ordered</th>
                                        <th style="width: 80px; min-width: 80px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($customer_orders as $customer_order)
                                        <tr>
                                            <td>
                                                @if(!empty($customer_order->image_filename))
                                                    <img src="{{ URL::asset('storage/customers/' . $customer_order->image_filename) }}" alt="" class="avatar-sm rounded-circle me-2">
                                                @else
                                                <div class="avatar-sm d-inline-block align-middle me-2">
                                                    <div class="avatar-title bg-soft-primary text-primary font-size-20 m-0 rounded-circle">
                                                        <i class="bx bxs-user-circle"></i>
                                                    </div>
                                                </div>
                                                @endif
                                                <a href="#" class="">{{ $customer_order->invoice_no }}</a>
                                                </td>

                                            <td>{{ $customer_order->customer->name }}</td>
                                            <td>
                                                @php $grandTotal = 0; @endphp

                                                {{-- Process Meals --}}
                                                @if($customer_order->meals->isNotEmpty())
                                                    @foreach ($customer_order->meals as $meal)
                                                        @php
                                                            $subtotal = $meal->price;
                                                            // * $meal->quantity
                                                            $grandTotal += $subtotal;
                                                        @endphp
                                                        {{ $meal->meal->name }} - ₹{{ number_format($subtotal, 2) }}<br>
                                                    @endforeach
                                                @endif

                                                {{-- Process Addons --}}
                                                @if($customer_order->addons->isNotEmpty())
                                                    @foreach ($customer_order->addons as $addon)
                                                        @php
                                                            $subtotal = $addon->price * $addon->quantity;
                                                            $grandTotal += $subtotal;
                                                        @endphp
                                                        {{ $addon->quantity }} {{ $addon->addon->name }} - ₹{{ number_format($subtotal, 2) }}<br>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                ₹{{ number_format($grandTotal, 2) }}
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ ($customer_order->pay_method == Utility::PAYMENT_ONLINE) ? 'primary' : 'secondary' }}">
                                                    {{ $customer_order->is_paid ? 'online' : 'COD' }}
                                                </span>
                                                <span class="badge bg-{{ $customer_order->is_paid ? 'success' : 'danger' }}">
                                                    {{ $customer_order->is_paid ? 'Paid' : 'Not Paid' }}
                                                </span>
                                                <span class="badge bg-{{ $customer_order->status ? 'success' : 'danger' }}">
                                                    {{ $customer_order->status ? 'Activated' : 'Not Activated' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="#" class="text-body">
                                                    On {{ $customer_order->created_at->format('d M Y') }}<br>

                                                </a>
                                            </td>

                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bx bx-dots-horizontal-rounded"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            @if($customer_order->status == Utility::ITEM_INACTIVE)
                                                                <li>
                                                                <a onclick="return confirm('Are you sure to Activate & Mark as Paid?')" class="dropdown-item" href="{{ route('admin.orders.activate', [encrypt($customer_order->id), 'paid']) }}">
                                                                    <i class="fas fa-credit-card font-size-16 text-success me-1"></i> Activate With Payment
                                                                </a>
                                                                </li>

                                                                <li>
                                                                <a onclick="return confirm('Are you sure to Activate without Payment?')" class="dropdown-item" href="{{ route('admin.orders.activate', [encrypt($customer_order->id), 'unpaid']) }}">
                                                                    <i class="fas fa-toggle-on font-size-16 text-success me-1"></i> Activate Without Payment
                                                                </a>
                                                                </li>
                                                            @endif
                                                            <li><a class="dropdown-item" onclick="return confirm('Are you sure to make the change?')" href="{{ route('admin.orders.changePayment',encrypt($customer_order->id))}}">{!! $customer_order->is_paid?'<i class="fas fa-power-off font-size-16 text-danger me-1"></i> Mark Un Paid':'<i class="fas fa-circle-notch font-size-16 text-primary me-1"></i> Mark Paid'!!}</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <!-- end table -->
                                <div class="pagination justify-content-center">{{ $customer_orders->appends(['status' => encrypt($is_active)])->links() }}</div>
                            </div>
                         <!-- end table responsive -->

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
