@extends('admin.layouts.master')
@section('title') @lang('translation.Branch_Details') @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
@endsection
@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Account_Manage') @endslot
@slot('li_2') @lang('translation.Branch_Manage') @endslot
@slot('title') Details of {{ $branch->name }} @endslot
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-xl-6">
                        <div class="mt-4 mt-xl-3">
                            <a href="javascript: void(0);" class="text-primary">Created on {{ $branch->created_at->format('d-m-Y') }}</a>
                            <h4 class="mt-1 mb-3">{{ $branch->name }}</h4>

                            {{-- <p class="text-muted float-start me-3">
                                @for ($x=1;$x<=$branch->my_review; $x++)
                                    <i class="bx bxs-star text-warning"></i>
                                @endfor
                                @for ($x=1;$x<=(Utility::MAX_REVIEW_LIMIT-$branch->my_review); $x++)
                                    <span class="bx bxs-star"></span>
                                @endfor
                            </p> --}}
                            {{-- <p class="text-muted mb-4">( {{ $branch->reviews()->count() }} Review(s) )</p> --}}

                            @unless (empty($branch->phone))
                                <h6 class="text-primary"><i class="fa fa-phone-square font-size-16 align-middle text-primary me-1"></i>{{ $branch->phone }}</h6>
                            @endunless
                            @unless (empty($branch->email))
                            <h6 class="text-success"><i class="fa fa-envelope font-size-16 align-middle text-success me-1"></i>{{ $branch->email }}</h6>
                            @endunless
                            @unless (empty($branch->website))
                            <h6 class="text-danger"><i class="fa fa-globe font-size-16 align-middle text-success me-1"></i>{{ $branch->website }}</h6>
                            @endunless
                            {{-- <h5 class="mb-4">Price : <span class="text-muted me-2"><del>$240 USD</del></span> <b>$225 USD</b></h5> --}}
                            @unless (empty($branch->address1))<p class="text-muted mb-0">{{ $branch->address1 }}</p>@endunless
                            @unless (empty($branch->address2))<p class="text-muted mb-0">{{ $branch->address2 }}</p>@endunless
                            @unless (empty($branch->address3))<p class="text-muted mb-0">{{ $branch->address3 }}</p>@endunless
                            @unless (empty($branch->city))
                            <p class="text-muted mb-0">{{ $branch->city }}</p>
                            @endunless
                            {{-- <p class="text-muted mb-0">{{ $branch->district }}</p> --}}
                            {{-- <p class="text-muted mb-0">{{ $branch->state }}</p> --}}
                            @unless (empty($branch->postal_code))
                            <p class="text-muted mb-4">{{ $branch->postal_code }}</p>
                            @endunless
                            {{-- <div class="row mb-3">
                                <div class="col-md-6">
                                    <div>
                                        <p class="text-muted"><i class="bx bx-unlink font-size-16 align-middle text-primary me-1"></i> Wireless</p>
                                        <p class="text-muted"><i class="bx bx-shape-triangle font-size-16 align-middle text-primary me-1"></i> Wireless Range : 10m</p>
                                        <p class="text-muted"><i class="bx bx-battery font-size-16 align-middle text-primary me-1"></i> Battery life : 6hrs</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <p class="text-muted"><i class="bx bx-user-voice font-size-16 align-middle text-primary me-1"></i> Bass</p>
                                        <p class="text-muted"><i class="bx bx-cog font-size-16 align-middle text-primary me-1"></i> Warranty : 1 Year</p>
                                    </div>
                                </div>
                            </div> --}}

                            {{-- <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Language ({{ $branch->lang }}) <i class="mdi mdi-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li><a class="dropdown-item" href="{{ route('admin.branches.language.change', [encrypt($branch->id), 'AR'] ) }}" >Arabic (AR)</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.branches.language.change', [encrypt($branch->id), 'EN'] ) }}" >English (EN)</a></li>
                                    </ul>
                                </div>
                            </div> --}}


                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="product-detai-imgs">
                            <div class="row">
                                {{-- <div class="col-md-2 col-sm-3 col-4">
                                    <div class="nav flex-column nav-pills " id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link active" id="product-1-tab" data-bs-toggle="pill" href="#product-1" role="tab" aria-controls="product-1" aria-selected="true">
                                            <img src="{{ URL::asset('assets/images/product/img-7.png') }}" alt="" class="img-fluid mx-auto d-block rounded">
                                        </a>
                                        <a class="nav-link" id="product-2-tab" data-bs-toggle="pill" href="#product-2" role="tab" aria-controls="product-2" aria-selected="false">
                                            <img src="{{ URL::asset('assets/images/product/img-8.png') }}" alt="" class="img-fluid mx-auto d-block rounded">
                                        </a>
                                        <a class="nav-link" id="product-3-tab" data-bs-toggle="pill" href="#product-3" role="tab" aria-controls="product-3" aria-selected="false">
                                            <img src="{{ URL::asset('assets/images/product/img-7.png') }}" alt="" class="img-fluid mx-auto d-block rounded">
                                        </a>
                                        <a class="nav-link" id="product-4-tab" data-bs-toggle="pill" href="#product-4" role="tab" aria-controls="product-4" aria-selected="false">
                                            <img src="{{ URL::asset('assets/images/product/img-8.png') }}" alt="" class="img-fluid mx-auto d-block rounded">
                                        </a>
                                    </div>
                                </div> --}}
                                <div class="col-md-6 offset-md-1 col-sm-9 col-8">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade show active" id="product-1" role="tabpanel" aria-labelledby="product-1-tab">
                                            <div>
                                                <img src="https://place-hold.it/800x800?text={{ $branch->name }}&fontsize=40" alt="" class="img-fluid mx-auto d-block">
                                            </div>
                                        </div>
                                        {{-- <div class="tab-pane fade" id="product-2" role="tabpanel" aria-labelledby="product-2-tab">
                                            <div>
                                                <img src="{{ URL::asset('assets/images/product/img-8.png') }}" alt="" class="img-fluid mx-auto d-block">
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="product-3" role="tabpanel" aria-labelledby="product-3-tab">
                                            <div>
                                                <img src="{{ URL::asset('assets/images/product/img-7.png') }}" alt="" class="img-fluid mx-auto d-block">
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="product-4" role="tabpanel" aria-labelledby="product-4-tab">
                                            <div>
                                                <img src="{{ URL::asset('assets/images/product/img-8.png') }}" alt="" class="img-fluid mx-auto d-block">
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div class="text-center">
                                        {{-- <a href="{{ route('admin.branches.order',encrypt($branch->id)) }}" class="btn btn-primary waves-effect waves-light mt-2 me-1">
                                            Active Orders
                                        </a>
                                        <a href="{{ route('admin.branches.history.order',encrypt($branch->id)) }}" class="btn btn-success waves-effect waves-light mt-2 me-1">
                                            Order History
                                        </a>
                                        <a href="{{ route('admin.branches.sales',encrypt($branch->id)) }}" class="btn btn-primary waves-effect waves-light mt-2 me-1">
                                            Sales
                                        </a>
                                        <a href="{{ route('admin.branches.history.sales',encrypt($branch->id)) }}" class="btn btn-success waves-effect waves-light mt-2 me-1">
                                            History Sales
                                        </a>
                                        <a href="{{ route('admin.branches.planners',encrypt($branch->id)) }}" class="btn btn-primary waves-effect waves-light mt-2 me-1">
                                            Planner
                                        </a>
                                        <a target="_blank" href="{{ route('admin.branches.listing',encrypt($branch->id)) }}" class="btn btn-success waves-effect waves-light mt-2 me-1">
                                            Listing
                                        </a>
                                        <a target="_blank" href="{{ route('admin.branches.warehouses',encrypt($branch->id)) }}" class="btn btn-primary waves-effect waves-light mt-2 me-1">
                                            Ware houses
                                        </a> --}}
                                        {{-- <a href="{{ route('admin.branches.sales',encrypt($branch->id)) }}" class="btn btn-success waves-effect waves-light mt-2 me-1">
                                            Sales
                                        </a>
                                        <a href="{{ route('admin.branches.history.order',encrypt($branch->id)) }}" class="btn btn-success waves-effect waves-light mt-2 me-1">
                                            Planner
                                        </a> --}}
                                        {{-- <a target="_blank" href="{{ route('admin.branches.tickets',encrypt($branch->id)) }}" class="btn btn-success waves-effect waves-light mt-2 me-1">
                                            Tickets
                                        </a> --}}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <!-- end Specifications -->
            </div>
        </div>
        <!-- end card -->
    </div>
</div>
<!-- end row -->
@endsection
@section('script')
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
