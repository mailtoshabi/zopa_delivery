@extends('admin.layouts.master')
@section('title') @lang('translation.Kitchen_List') @endsection
@section('css')
<link href="{{ URL::asset('/assets/libs/datatables.net-bs4/datatables.net-bs4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/datatables.net-responsive-bs4/datatables.net-responsive-bs4.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Account_Manage') @endslot
@slot('li_2') @lang('translation.Kitchen_Manage') @endslot
@slot('title') @lang('translation.Kitchen_List') @endslot
@endcomponent
@if(session()->has('success'))
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i><strong>Success</strong> - {{ session()->get('success') }}
</div>
@endif
{{-- <div class="row">
    <div class="col-lg-12">
    <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link @if($is_approved==0) active @endif" @if($is_approved==0)aria-current="page"@endif href="{{ route('admin.kitchens.index','status='.encrypt(0)) }}">Pending</a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if($is_approved==1) active @endif" @if($is_approved==1)aria-current="page"@endif href="{{ route('admin.kitchens.index','status='.encrypt(1)) }}">Approved</a>
        </li>
      </ul>
    </div>
</div> --}}
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-0">
            <div class="card-body">
                <!-- Nav tabs -->
                {{-- <ul class="nav nav-tabs" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link {{ $status==Utility::ITEM_INACTIVE?'active':'' }}" data-plugin="render-tab" data-tab=".kitchendetailsTab" href="{{ route('admin.kitchens.show.tab', encrypt(Utility::ITEM_INACTIVE)) }}" role="tab">
                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                            <span class="d-none d-sm-block">Non Verified Kitchens</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ $status==Utility::ITEM_ACTIVE?'active':'' }}" data-plugin="render-tab" data-tab=".kitchendetailsTab" href="{{ route('admin.kitchens.show.tab', encrypt(Utility::ITEM_ACTIVE)) }}"  role="tab">
                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                            <span class="d-none d-sm-block">Verified Kitchens</span>
                        </a>
                    </li>

                </ul> --}}
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane kitchendetailsTab active" role="tabpanel">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="mb-3">
                                <h5 class="card-title">@lang('translation.Kitchen_List') <span class="text-muted fw-normal ms-2">({{ $kitchens->total() }})</span></h5>
                                </div>
                            </div>

                            {{-- <div class="col-md-6">
                                <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mb-3">
                                    <div>
                                        <a href="{{ route('admin.kitchens.create') }}" class="btn btn-light"><i class="bx bx-plus me-1"></i> Add New</a>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                         <!-- end row -->

                        <div class="table-responsive mb-4">
                            <table class="table align-middle dt-responsive table-check nowrap" style="border-collapse: collapse; border-spacing: 0 8px; width: 100%;">
                                <thead>
                                <tr>
                                    {{-- <th scope="col" style="width: 50px;">
                                        <div class="form-check font-size-16">
                                            <input type="checkbox" class="form-check-input" id="checkAll">
                                            <label class="form-check-label" for="checkAll"></label>
                                        </div>
                                    </th> --}}
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">City</th>
                                    <th scope="col">Created</th>
                                    <th style="width: 80px; min-width: 80px;">View</th>
                                </tr>
                                </thead>
                                <tbody>
                                   @foreach ($kitchens as $kitchen)
                                       <tr>
                                           {{-- <th scope="row">
                                               <div class="form-check font-size-16">
                                                   <input type="checkbox" class="form-check-input" id="contacusercheck1">
                                                   <label class="form-check-label" for="contacusercheck1"></label>
                                               </div>
                                           </th> --}}
                                           <td>{{ $kitchen->id }}</td>
                                           <td>
                                               @if(!empty($kitchen->image_filename))
                                                   <img src="{{ URL::asset('storage/kitchens/' . $kitchen->image_filename) }}" alt="" class="avatar-sm rounded-circle">
                                               @else
                                               <div class="avatar-sm d-inline-block align-middle me-2">
                                                   <div class="avatar-title bg-soft-primary text-primary font-size-20 m-0 rounded-circle">
                                                       <i class="bx bxs-user-circle"></i>
                                                   </div>
                                               </div>
                                               @endif
                                               <a href="{{ route('admin.kitchens.edit',encrypt($kitchen->id)) }}" class="">{{ $kitchen->name }} </a>
                                            </td>

                                           <td>{{ $kitchen->phone }}</td>
                                           <td>{{ $kitchen->email }}</td>
                                           <td>
                                            {{ $kitchen->city }}
                                            <br> <small>{{ $kitchen->district->name }} District</small>
                                           </td>
                                           <td>
                                            <a href="#" class="text-body">
                                                On {{ $kitchen->created_at->format('d M Y') }}
                                            </a>
                                        </td>
                                           {{-- <td>
                                                <a href="{{ route('admin.kitchens.view',encrypt($kitchen->id))}}" class="btn btn-primary btn-sm btn-rounded" >
                                                    Details
                                                </a>
                                            </td> --}}
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bx bx-dots-horizontal-rounded"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="{{ route('admin.kitchens.edit',encrypt($kitchen->id))}}"><i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit</a></li>
                                                        {{-- <li><a class="dropdown-item" href="{{ route('admin.kitchens.destroy',encrypt($kitchen->id))}}"><i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete</a></li> --}}
                                                        @if(!$kitchen->is_approved)
                                                        <li><a href="#" class="dropdown-item" data-plugin="delete-data" data-target-form="#form_delete_{{ $loop->iteration }}"><i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete</a></li>
                                                        <form id="form_delete_{{ $loop->iteration }}" method="POST" action="{{ route('admin.kitchens.destroy',encrypt($kitchen->id))}}">
                                                            @csrf
                                                            <input type="hidden" name="_method" value="DELETE" />
                                                        </form>
                                                        @endif
                                                        <li><a class="dropdown-item" href="{{ route('admin.kitchens.changeStatus',encrypt($kitchen->id))}}">{!! $kitchen->status?'<i class="fas fa-power-off font-size-16 text-danger me-1"></i> Unpublish':'<i class="fas fa-circle-notch font-size-16 text-primary me-1"></i> Publish'!!}</a></li>
                                                        {{-- <li><a class="dropdown-item" href="{{ route('admin.kitchens.makeDefault',encrypt($kitchen->id))}}"><i class="mdi mdi-cursor-pointer font-size-16 text-success me-1"></i> Make Default</a></li> --}}
                                                        {{-- <li><a class="dropdown-item" href="{{ route('admin.kitchens.view',encrypt($kitchen->id))}}"><i class="fa fa-eye font-size-16 text-success me-1"></i> Details</a></li> --}}
                                                    </ul>
                                                </div>
                                            </td>
                                       </tr>
                                   @endforeach
                                </tbody>
                            </table>
                            <!-- end table -->
                            <div class="pagination justify-content-center">{{ $kitchens->links() }}</div>
                        </div>
                         <!-- end table responsive -->

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
<script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/datatable-pages.init.js') }}"></script>
{{-- <script>
    $(document).ready(function() {
        $(document).on('click','[data-plugin="render-tab"]',function(e) {
            e.preventDefault();

            $('.nav-link').removeClass('active');
            $(this).addClass('active');
            var url = $(this).data('target');
            var tab_Id = $(this).data('tab');
            renderTab(url,tab_Id);
        });
    });

    var url = "{{ route('admin.kitchens.show.tab', Utility::ITEM_INACTIVE) }}";
    var tab_Id = ".kitchendetailsTab";
    renderTab(url,tab_Id);

    function renderTab(url,tab_Id) {
        $.get(url, function (data) {
            var $el = $(data.html).clone();
            $el = $(tab_Id).html($el);
            $(tab_Id).tab('show');
        });
    }
</script> --}}
@endsection
