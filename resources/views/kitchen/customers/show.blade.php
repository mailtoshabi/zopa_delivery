@extends('kitchen.layouts.master')
@section('title') Details of {{ $customer->name }} @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
@endsection
@section('content')
@component('kitchen.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Account_Manage') @endslot
@slot('li_2') @lang('translation.Customer_Manage') @endslot
@slot('title') Details of {{ $customer->name }} @endslot
@endcomponent
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-xl-6">
                        <div class="mt-4 mt-xl-3">
                            <a href="javascript: void(0);" class="text-primary">Created on {{ $customer->created_at->format('d-m-Y') }}</a>
                            <h4 class="mt-1 mb-3">{{ $customer->name }}</h4>

                            {{-- <p class="text-muted float-start me-3">
                                @for ($x=1;$x<=$customer->my_review; $x++)
                                    <i class="bx bxs-star text-warning"></i>
                                @endfor
                                @for ($x=1;$x<=(Utility::MAX_REVIEW_LIMIT-$customer->my_review); $x++)
                                    <span class="bx bxs-star"></span>
                                @endfor
                            </p> --}}
                            {{-- <p class="text-muted mb-4">( {{ $customer->reviews()->count() }} Review(s) )</p> --}}

                            @unless (empty($customer->phone))
                                <h6 class="text-primary"><i class="fa fa-phone-square font-size-16 align-middle text-primary me-1"></i>{{ $customer->phone }}</h6>
                            @endunless
                            @unless (empty($customer->email))
                            <h6 class="text-success"><i class="fa fa-envelope font-size-16 align-middle text-success me-1"></i>{{ $customer->email }}</h6>
                            @endunless
                            @unless (empty($customer->website))
                            <h6 class="text-danger"><i class="fa fa-globe font-size-16 align-middle text-success me-1"></i>{{ $customer->website }}</h6>
                            @endunless
                            {{-- <h5 class="mb-4">Price : <span class="text-muted me-2"><del>$240 USD</del></span> <b>$225 USD</b></h5> --}}
                            @unless (empty($customer->address1))<p class="text-muted mb-0">{{ $customer->address1 }}</p>@endunless
                            @unless (empty($customer->address2))<p class="text-muted mb-0">{{ $customer->address2 }}</p>@endunless
                            @unless (empty($customer->address3))<p class="text-muted mb-0">{{ $customer->address3 }}</p>@endunless
                            @unless (empty($customer->city))
                            <p class="text-muted mb-0">{{ $customer->city }}</p>
                            @endunless
                            {{-- <p class="text-muted mb-0">{{ $customer->district }}</p> --}}
                            {{-- <p class="text-muted mb-0">{{ $customer->state }}</p> --}}
                            @unless (empty($customer->postal_code))
                            <p class="text-muted mb-4">{{ $customer->postal_code }}</p>
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
                                        Language ({{ $customer->lang }}) <i class="mdi mdi-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li><a class="dropdown-item" href="{{ route('kitchen.customers.language.change', [encrypt($customer->id), 'AR'] ) }}" >Arabic (AR)</a></li>
                                        <li><a class="dropdown-item" href="{{ route('kitchen.customers.language.change', [encrypt($customer->id), 'EN'] ) }}" >English (EN)</a></li>
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
                                                <img src="https://place-hold.it/800x800?text={{ $customer->name }}&fontsize=40" alt="" class="img-fluid mx-auto d-block">
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
                                        {{-- <a href="{{ route('kitchen.customers.order',encrypt($customer->id)) }}" class="btn btn-primary waves-effect waves-light mt-2 me-1">
                                            Active Orders
                                        </a>
                                        <a href="{{ route('kitchen.customers.history.order',encrypt($customer->id)) }}" class="btn btn-success waves-effect waves-light mt-2 me-1">
                                            Order History
                                        </a>
                                        <a href="{{ route('kitchen.customers.sales',encrypt($customer->id)) }}" class="btn btn-primary waves-effect waves-light mt-2 me-1">
                                            Sales
                                        </a>
                                        <a href="{{ route('kitchen.customers.history.sales',encrypt($customer->id)) }}" class="btn btn-success waves-effect waves-light mt-2 me-1">
                                            History Sales
                                        </a>
                                        <a href="{{ route('kitchen.customers.planners',encrypt($customer->id)) }}" class="btn btn-primary waves-effect waves-light mt-2 me-1">
                                            Planner
                                        </a>
                                        <a target="_blank" href="{{ route('kitchen.customers.listing',encrypt($customer->id)) }}" class="btn btn-success waves-effect waves-light mt-2 me-1">
                                            Listing
                                        </a>
                                        <a target="_blank" href="{{ route('kitchen.customers.warehouses',encrypt($customer->id)) }}" class="btn btn-primary waves-effect waves-light mt-2 me-1">
                                            Ware houses
                                        </a> --}}
                                        {{-- <a href="{{ route('kitchen.customers.sales',encrypt($customer->id)) }}" class="btn btn-success waves-effect waves-light mt-2 me-1">
                                            Sales
                                        </a>
                                        <a href="{{ route('kitchen.customers.history.order',encrypt($customer->id)) }}" class="btn btn-success waves-effect waves-light mt-2 me-1">
                                            Planner
                                        </a> --}}
                                        {{-- <a target="_blank" href="{{ route('kitchen.customers.tickets',encrypt($customer->id)) }}" class="btn btn-success waves-effect waves-light mt-2 me-1">
                                            Tickets
                                        </a> --}}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                {{-- <div class="mt-5">
                    <h5 class="mb-3">Specifications :</h5>

                    <div class="table-responsive">
                        <table class="table mb-0 table-bordered">
                            <tbody>
                                <tr>
                                    <th scope="row" style="width: 400px;">Category</th>
                                    <td>Headphone</td>
                                </tr>
                                <tr>
                                    <th scope="row">Brand</th>
                                    <td>JBL</td>
                                </tr>
                                <tr>
                                    <th scope="row">Color</th>
                                    <td>Black</td>
                                </tr>
                                <tr>
                                    <th scope="row">Connectivity</th>
                                    <td>Bluetooth</td>
                                </tr>
                                <tr>
                                    <th scope="row">Warranty Summary</th>
                                    <td>1 Year</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div> --}}
                <!-- end Specifications -->
                @if($customer->contactPersons->count() != 0)
                    <div class="mt-5">
                        <h5>Contact People</h5>
                        {{-- //TODO: do paginate here. ajax paginate OR do limit and give a view all button--}}
                        @foreach ( $customer->contactPersons as $contactPerson )
                            <div class="mt-4 border p-4">

                                <div class="row">
                                    <div class="col-xl-12 col-md-12">
                                        <div>
                                            <div class="d-flex">
                                                {{-- <img src="{{ URL::asset('assets/images/users/avatar-2.jpg') }}" class="avatar-sm rounded-circle" alt="img" /> --}}
                                                <img src="https://place-hold.it/100x100?text={{ substr($contactPerson->name, 0, 1) }}&fontsize=40" alt="" class="avatar-sm rounded-circle">
                                                <div class="flex-1 ms-4">
                                                    <h5 class="mb-2 font-size-15 text-primary">{{ $contactPerson->name }}</h5>
                                                    <h5 class="text-muted font-size-15">@unless(empty($contactPerson->phone)) {{ $contactPerson->phone }}@endunless @unless(empty($contactPerson->email)) | {{ $contactPerson->email }} @endunless</h5>
                                                    {{-- <p class="text-muted">65 Followers, 86 Reviews</p> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-9 col-md-7">
                                        <div>
                                            {{-- <p class="text-muted mb-2">
                                                @for ($x=1;$x<=$review->rating; $x++)
                                                    <i class="bx bxs-star text-warning"></i>
                                                @endfor
                                                @for ($x=1;$x<=(Utility::MAX_REVIEW_LIMIT-$review->rating); $x++)
                                                    <span class="bx bxs-star"></span>
                                                @endfor
                                                <span class="ms-3"><i class="far fa-calendar-alt text-primary me-1"></i> {{ $review->created_at->format('d/m/Y') }}</span>
                                            </p> --}}

                                            {{-- <p class="text-muted">{{ $review->description }}</p> --}}
                                            {{-- <ul class="list-inline float-sm-end mb-sm-0">
                                                <li class="list-inline-item">
                                                    <a href="javascript: void(0);"><i class="far fa-thumbs-up me-1"></i> Like</a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript: void(0);"><i class="far fa-comment-dots me-1"></i> Comment</a>
                                                </li>
                                            </ul> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                @endif
            </div>
        </div>
        <!-- end card -->
    </div>
</div>
<!-- end row -->
@endsection

@section('script')


@endsection
