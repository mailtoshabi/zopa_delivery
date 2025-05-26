@extends('admin.layouts.master')
@section('title') @lang('translation.Meal_Wallet') @endsection
@section('css')
<link href="{{ URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
@component('admin.dir_components.breadcrumb')
    @slot('li_1') @lang('translation.Meal_Manage') @endslot
    @slot('li_2') @lang('translation.Meal_Wallet') @endslot
    @slot('title') @lang('translation.Meal_Wallet') @endslot
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
                                <h5 class="card-title">Meal Wallets <span class="text-muted fw-normal ms-2">({{ $wallets->total() }})</span></h5>
                            </div>
                        </div>

                        <div class="table-responsive mb-4">
                            <div class="mb-3">
                                <button id="bulk-status-toggle" class="btn btn-outline-primary">Toggle Status (Selected)</button>
                            </div>

                            <table class="table align-middle dt-responsive table-check nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th>Wallet Quantity</th>
                                        <th>Status</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wallets as $wallet)
                                    <tr>
                                        <td><input type="checkbox" class="wallet-checkbox" value="{{ $wallet->id }}"></td>
                                        <td>{{ $wallet->customer->name ?? '-' }}</td>
                                        <td>{{ $wallet->customer->phone ?? '-' }}</td>
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
                                                        <a class="dropdown-item" href="{{ route('admin.customers.wallets.toggleWalletStatus', encrypt($wallet->id)) }}">
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
<script src="{{ URL::asset('assets/js/app.min.js') }}"></script>
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

    fetch("{{ route('admin.customers.wallets.bulkToggle') }}", {
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
@endsection
