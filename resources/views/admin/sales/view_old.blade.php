@extends('admin.layouts.invoice')
@section('title') @lang('translation.Proforma_Details') @endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">

                        <h6 class="text-primary">Invoice To</h6>
                        <p class="mb-2">{{ $sale->estimate->customer->name }} </p>
                        <p class="text-muted mb-0">{{ $sale->estimate->customer->building_no }}, {{ $sale->estimate->customer->street }}</p>
                        <p class="text-muted mb-0">{{ $sale->estimate->customer->district }}</p>
                        <p class="text-muted mb-0">{{ $sale->estimate->customer->city }}</p>
                        <p class="text-muted mb-2">{{ $sale->estimate->customer->postal_code }}</p>
                        <p class="text-primary mb-0">Mob:{{ $sale->estimate->customer->phone }}</p>
                        <p class="text-success mb-2">Email:{{ $sale->estimate->customer->email }}</p>
                    </div>
                    <div class="col-sm-6">
                        <br>
                        <p class="mb-0">Date : {{ $sale->created_at->format('d-m-Y') }}</p>
                        <p class="mb-2">Order ID : {{ $sale->invoice_no }} </p>
                    </div>

                </div>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Description of Goods</th>
                                    <th scope="col">HSN/SAC</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Rate</th>
                                    <th scope="col">per</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale->products as $product )
                                    <tr>
                                        <th scope="row">
                                        @if(!empty($uom->image))
                                            <div>
                                                <img src="{{ URL::asset(App\Models\Product::DIR_STORAGE . $product->image) }}" alt="" class="avatar-sm rounded-circle me-2">
                                            </div>
                                        @else
                                            <div class="avatar-sm d-inline-block align-middle me-2">
                                                <div class="avatar-title bg-soft-primary text-primary font-size-20 m-0 rounded-circle">
                                                    <i class="bx bxs-user-circle"></i>
                                                </div>
                                            </div>
                                        @endif
                                        </th>
                                        <td>
                                            <div>
                                                <h5 class="text-truncate font-size-14">{{$product->name }}
                                                    <small>{{ $product->description }}</small>
                                                </h5>
                                            </div>
                                        </td>
                                        <td>{{ $product->hsn->name }}</td>
                                        <td>{{ $product->pivot->quantity }} {{ $product->uom->name }}</td>
                                        <td>{{ Utility::CURRENCY_DISPLAY . ' ' . $product->pivot->price }}</td>
                                        <td>{{ $product->uom->name }}</td>
                                        <td>{{ Utility::CURRENCY_DISPLAY . ' ' . $product->pivot->price*$product->pivot->quantity }}</td>

                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="5">
                                        <h6 class="m-0 text-right">&nbsp;</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <h6 class="m-0 text-right">Sub Total:</h6>
                                    </td>
                                    <td>
                                        <h6 class="m-0 text-right">{{ Utility::CURRENCY_DISPLAY . ' ' . $sale->sub_total; }}</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <h6 class="m-0 text-right">Total VAT:</h6>
                                    </td>
                                    <td>
                                        <h6 class="m-0 text-right">{{ $sale->vat . ' ' . Utility::CURRENCY_DISPLAY }}</h6>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <h5 class="m-0 text-right">Grand Total:</h5>
                                    </td>
                                    <td>
                                        <h5 class="m-0 text-right">{{ (($sale->price) + ($sale->vat)) . ' ' . Utility::CURRENCY_DISPLAY }}</h5>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        </div>


    </div>
</div>
<!-- end row -->
@endsection
