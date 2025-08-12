@extends('admin.layouts.master')
@section('title') @lang('translation.Customers') @endsection
@section('css')
<link href="{{ URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Account_Manage') @endslot
@slot('li_2') @lang('translation.Customer_Manage') @endslot
@slot('title') @lang('translation.Customer_List') @endslot
@endcomponent
@if(session()->has('success'))
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i><strong>Success</strong> - {{ session()->get('success') }}
</div>
@endif
<!-- Bootstrap Tabs -->
    <ul class="nav nav-tabs" id="customerTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="individual-tab" data-bs-toggle="tab" href="#individuals" role="tab">
                Individual
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="institution-tab" data-bs-toggle="tab" href="#institutions" role="tab">
                Institution
            </a>
        </li>
    </ul>
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-body">
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane customerdetailsTab active" role="tabpanel">
                        <div class="row align-items-center">

                            <div class="tab-content mt-3" id="customerTabsContent">
                                <div class="tab-pane fade show active" id="individuals" role="tabpanel">
                                    @include('admin.customers.table', ['customers' => $individuals])
                                </div>
                                <div class="tab-pane fade" id="institutions" role="tabpanel">
                                    @include('admin.customers.table', ['customers' => $institutions])
                                </div>
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
