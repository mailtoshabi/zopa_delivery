@extends('kitchen.layouts.master')
@section('title') @lang('translation.Addon_Wallet') @endsection
@section('css')
<link href="{{ URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
@component('kitchen.dir_components.breadcrumb')
    @slot('li_1') @lang('translation.Addon_Manage') @endslot
    @slot('li_2') @lang('translation.Addon_Wallet') @endslot
    @slot('title') @lang('translation.Addon_Wallet') @endslot
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
                                <h5 class="card-title">Addon Wallets <span class="text-muted fw-normal ms-2">({{ $wallets->total() }})</span></h5>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <div class="mb-3">
                                <button id="bulk-status-toggle" class="btn btn-outline-primary">Toggle Status (Selected)</button>
                            </div>

                            <!-- Filter Form Start -->
                            <form method="GET" class="row mb-3" id="customer-filter-form" autocomplete="off">
                                <div class="col-md-4">
                                    <select name="customer_id" class="form-select" onchange="document.getElementById('customer-filter-form').submit();">
                                        <option value="">All Customers</option>
                                        @foreach($customers as $customer)
                                            {{-- <option value="{{ encrypt($customer->id) }}" {{ decrypt(request('customer_id')) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} ({{ $customer->phone }})
                                            </option> --}}
                                            <option value="{{ encrypt($customer->id) }}" {{ $selectedCustomerId == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} ({{ $customer->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        Filter
                                    </button>
                                    <a href="{{ route('kitchen.customers.wallets') }}" class="btn btn-link">Reset</a>
                                </div>
                            </form>
                            <!-- Filter Form End -->

                            <table class="table align-middle dt-responsive table-check nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>Customer</th>
                                        <th>Addon</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wallets as $wallet)
                                    <tr>
                                        <td><input type="checkbox" class="wallet-checkbox" value="{{ $wallet->id }}"></td>
                                        <td>{{ $wallet->customer->name ?? '-' }}
                                            <br><small>{{ $wallet->customer->phone }}</small>
                                        </td>
                                        <td>{{ $wallet->addon->name ?? '-' }}</td>
                                        <td><strong>{{ $wallet->quantity }}</strong></td>
                                        <td>{!! $wallet->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Suspended</span>' !!}</td>
                                        <td>{{ $wallet->updated_at->format('d M Y, h:i A') }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('kitchen.customers.addons.toggleWalletStatus', encrypt($wallet->id)) }}">
                                                            {!! $wallet->status
                                                                ? '<i class="fas fa-power-off font-size-16 text-danger me-1"></i> Suspend'
                                                                : '<i class="fas fa-circle-notch font-size-16 text-success me-1"></i> Activate' !!}
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
                                {{ $wallets->links() }}
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

<script>
document.getElementById('select-all').addEventListener('click', function () {
    let checkboxes = document.querySelectorAll('.wallet-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

document.getElementById('bulk-status-toggle').addEventListener('click', function () {
    let selected = Array.from(document.querySelectorAll('.wallet-checkbox:checked')).map(cb => cb.value);

    if (selected.length === 0) {
        alert('Please select at least one wallet.');
        return;
    }

    if (!confirm('Are you sure you want to toggle the status for selected wallets?')) return;

    fetch("{{ route('kitchen.customers.addons.bulkToggle') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({ ids: selected })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Something went wrong!');
        }
    });
});
</script>
<script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
<script>
   $(document).ready(function() {
      $('select[name="customer_id"]').select2({
         placeholder: "Select a customer",
         allowClear: true
      });
   });
</script>
@endsection
