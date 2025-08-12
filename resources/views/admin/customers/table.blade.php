<div class="col-md-6">
    <div class="mb-3">
    <h5 class="card-title">@lang('translation.Customer_List') <span class="text-muted fw-normal ms-2">({{ $customers->total() }})</span></h5>
    </div>
</div>

<div class="table-responsive mb-4">
    <table class="table align-middle dt-responsive table-check nowrap" style="border-collapse: collapse; border-spacing: 0 8px; width: 100%;">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Kitchen</th>
            <th scope="col">Mobile</th>
            <th scope="col">Office Name</th>
            <th scope="col">City</th>
            <th scope="col">Status</th>
            <th style="width: 80px; min-width: 80px;">View</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($customers as $customer)
            <tr>
                <td>
                    @if(!empty($customer->image_filename))
                        <img src="{{ URL::asset('storage/customers/' . $customer->image_filename) }}" alt="" class="avatar-sm rounded-circle me-2">
                    @else
                    <div class="avatar-sm d-inline-block align-middle me-2">
                        <div class="avatar-title bg-soft-primary text-primary font-size-20 m-0 rounded-circle">
                            <i class="bx bxs-user-circle"></i>
                        </div>
                    </div>
                    @endif
                    <a href="{{ route('admin.customers.edit',encrypt($customer->id)) }}" class="">{{ $customer->name }}</a>
                </td>

                <td>{{ $customer->kitchen->display_name }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->office_name }}
                    @if(!empty($customer->landmark))<br> <small>{{ $customer->landmark }}</small>@endif
                </td>
                <td>
                    {{ $customer->city }}
                    <br> <small>{{ $customer->district->name }} District</small>
                </td>
                <td>
                    <span class="badge bg-{{ $customer->is_approved ? 'success' : 'danger' }}">
                        {{ $customer->is_approved ? 'Activated' : 'Suspended' }}
                    </span>
                    <span class="badge bg-{{ empty($customer->office_name) ? 'danger' : '' }}">
                        {{ empty($customer->office_name) ? 'Address Not Completed' : '' }}
                    </span>
                </td>

                    <td>
                        <div class="dropdown">
                            <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-horizontal-rounded"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('admin.customers.edit',encrypt($customer->id))}}"><i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit</a></li>
                                {{-- <li><a href="#" class="dropdown-item" data-plugin="delete-data" data-target-form="#form_delete_{{ $loop->iteration }}"><i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete</a></li>
                                <form id="form_delete_{{ $loop->iteration }}" method="POST" action="{{ route('admin.customers.destroy',encrypt($customer->id))}}">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE" />
                                </form> --}}
                                    <li><a class="dropdown-item" onclick="return confirm('Are you sure to make the change?')" href="{{ route('admin.customers.changeStatus',encrypt($customer->id))}}">{!! $customer->is_approved?'<i class="fas fa-power-off font-size-16 text-danger me-1"></i> Suspend':'<i class="fas fa-circle-notch font-size-16 text-primary me-1"></i> Activate'!!}</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.customers.show',encrypt($customer->id))}}"><i class="fa fa-eye font-size-16 text-success me-1"></i> Details</a></li>
                            </ul>
                        </div>
                    </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <!-- end table -->
    <div class="pagination justify-content-center">{{ $customers->links() }}</div>
</div>
