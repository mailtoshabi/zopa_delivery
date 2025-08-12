@extends('admin.layouts.master')
@section('title') @lang('translation.Product_List') @endsection
@section('css')
<link href="{{ URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Catalogue_Manage') @endslot
@slot('li_2') @lang('translation.Product_Manage') @endslot
@slot('title') @lang('translation.Product_List') @endslot
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
          <a class="nav-link @if($is_approved==Utility::ITEM_INACTIVE) active @endif" @if($is_approved==Utility::ITEM_INACTIVE)aria-current="page"@endif href="{{ route('admin.products.index','status='.encrypt(Utility::ITEM_INACTIVE)) }}">Pending <span class="badge rounded-pill bg-soft-danger text-danger float-end">{{ $count_new }}</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if($is_approved==Utility::ITEM_ACTIVE) active @endif" @if($is_approved==Utility::ITEM_ACTIVE)aria-current="page"@endif href="{{ route('admin.products.index','status='.encrypt(Utility::ITEM_ACTIVE)) }}">Approved</a>
        </li>
      </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-body">
                 <div class="row align-items-center">
                     <div class="col-md-6">
                         <div class="mb-3">
                             <h5 class="card-title">@lang('translation.Product_List') <span class="text-muted fw-normal ms-2">({{ $products->count() }})</span></h5>
                         </div>
                     </div>

                     <div class="col-md-6">
                         <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mb-3">
                             {{-- <div>
                                 <ul class="nav nav-pills">
                                     <li class="nav-item">
                                         <a class="nav-link active" href="apps-contacts-list" data-bs-toggle="tooltip" data-bs-placement="top" title="List"><i class="bx bx-list-ul"></i></a>
                                     </li>
                                     <li class="nav-item">
                                         <a class="nav-link" href="apps-contacts-grid" data-bs-toggle="tooltip" data-bs-placement="top" title="Grid"><i class="bx bx-grid-alt"></i></a>
                                     </li>
                                 </ul>
                             </div> --}}
                             <div>
                                 <a href="{{ route('admin.products.create') }}" class="btn btn-light"><i class="bx bx-plus me-1"></i> Add New Product</a>
                             </div>

                             {{-- <div class="dropdown">
                                 <a class="btn btn-link text-muted py-1 font-size-16 shadow-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                     <i class="bx bx-dots-horizontal-rounded"></i>
                                 </a>

                                 <ul class="dropdown-menu dropdown-menu-end">
                                     <li><a class="dropdown-item" href="#">Edit</a></li>
                                     <li><a class="dropdown-item" href="#">Delete</a></li>
                                     <li><a class="dropdown-item" href="#">Deactivate</a></li>
                                 </ul>
                             </div> --}}
                         </div>

                     </div>
                 </div>
                 <!-- end row -->

                 <div class="table-responsive mb-4">
                     <table class="table align-middle dt-responsive table-check nowrap" style="border-collapse: collapse; border-spacing: 0 8px; width: 100%;">
                         <thead>
                         <tr>
                             <th scope="col" style="width: 50px;">
                                 <div class="form-check font-size-16">
                                     <input type="checkbox" class="form-check-input" id="checkAll">
                                     <label class="form-check-label" for="checkAll"></label>
                                 </div>
                             </th>
                             <th scope="col">Name</th>
                             <th scope="col">Category</th>
                             <th scope="col">Price</th>
                             <th scope="col">Created By</th>
                             <th style="width: 80px; min-width: 80px;">Action</th>
                         </tr>
                         </thead>
                         <tbody>
                            @foreach ($products as $product)

                                <tr>
                                    <th scope="row">
                                        <div class="form-check font-size-16">
                                            <input type="checkbox" class="form-check-input" id="contacusercheck1">
                                            <label class="form-check-label" for="contacusercheck1"></label>
                                        </div>
                                    </th>
                                    <td>
                                        @if(!empty($product->image))
                                            <img src="{{ URL::asset(App\Models\Product::DIR_STORAGE . $product->image) }}" alt="" class="avatar-sm rounded-circle me-2">
                                        @else
                                        <div class="avatar-sm d-inline-block align-middle me-2">
                                            <div class="avatar-title bg-soft-primary text-primary font-size-20 m-0 rounded-circle">
                                                <i class="bx bxs-user-circle"></i>
                                            </div>
                                        </div>
                                        @endif

                                        <a href="{{ route('admin.products.edit',encrypt($product->id)) }}" class="">{{ $product->name }}
                                        @unless (empty($product->code))
                                            {{ ' - '.$product->code }}
                                        @endunless </a>
                                        @unless (empty($product->description))
                                           <p style="padding-left: 43px;"><small>{{ \Illuminate\Support\Str::limit($product->description, $limit = 60   , $end = '...') }}</small></p>
                                        @endunless
                                    </td>
                                    <td><a target="_blank" href="{{ route('admin.categories.edit',encrypt($product->category->id))}}" class="">{{ $product->category->name }} </a></td>
                                    <td>{{ Utility::CURRENCY_DISPLAY . ' '. $product->total_price }}</td>
                                    <td>

                                    </td>

                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-horizontal-rounded"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('admin.products.edit',encrypt($product->id))}}"><i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit</a></li>
                                                {{-- <li><a class="dropdown-item" href="{{ route('admin.products.destroy',encrypt($product->id))}}"><i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete</a></li> --}}
                                                @if(!$product->is_approved)
                                                <li><a href="#" class="dropdown-item" data-plugin="delete-data" data-target-form="#form_delete_{{ $loop->iteration }}"><i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete</a></li>
                                                <form id="form_delete_{{ $loop->iteration }}" method="POST" action="{{ route('admin.products.destroy',encrypt($product->id))}}">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE" />
                                                </form>
                                                @endif

                                                @if($product->is_approved)
                                                    <li><a class="dropdown-item" href="{{ route('admin.products.changeStatus',encrypt($product->id))}}">{!! $product->status?'<i class="fas fa-power-off font-size-16 text-danger me-1"></i> Unpublish':'<i class="fas fa-circle-notch font-size-16 text-primary me-1"></i> Publish'!!}</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                             @endforeach

                         </tbody>
                     </table>
                     <!-- end table -->
                     <div class="pagination justify-content-center">{{ $products->appends(['status' => encrypt($is_approved)])->links() }}</div>
                 </div>
                 <!-- end table responsive -->
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
