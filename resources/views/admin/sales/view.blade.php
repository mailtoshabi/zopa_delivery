@extends('admin.layouts.master')
@section('title') @lang('translation.Proforma_Details') @endsection
@section('content')
@unless (empty($sale->reason))
<div class="alert alert-danger alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-danger"></i><strong>Proforma Status : {{ Utility::saleStatus()[$sale->status]['name'] }}</strong> | <strong>Notes : </strong> <span class="">- {{ $sale->reason }}</span>
</div>
@endunless
<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">

                        <h6 class="text-primary">Invoice To</h6>
                        <p class="mb-2"><b>{{ $sale->estimate->customer->name }}</b> </p>
                        @unless (empty($sale->estimate->customer->trade_name))
                            <p class="mb-2">{{ $sale->estimate->customer->trade_name }} (Trade Name) </p>
                        @endunless
                        @unless (empty($sale->estimate->customer->address1))<p class="text-muted mb-0">{{ $sale->estimate->customer->address1 }}</p>@endunless
                        @unless (empty($sale->estimate->customer->address2))<p class="text-muted mb-0">{{ $sale->estimate->customer->address2 }}</p>@endunless
                        @unless (empty($sale->estimate->customer->address3))<p class="text-muted mb-0">{{ $sale->estimate->customer->address3 }}</p>@endunless
                        <p class="text-muted mb-0">{{ $sale->estimate->customer->city }}</p>
                        <p class="text-muted mb-0">{{ $sale->estimate->customer->district->name }} District</p>
                        <p class="text-muted mb-0">{{ $sale->estimate->customer->state->name }} - {{ $sale->estimate->customer->postal_code }}</p>
                        <p class="text-primary mb-0">Mob:{{ $sale->estimate->customer->phone }}</p>
                        @unless (empty($sale->estimate->customer->email))<p class="text-success mb-2">Email:{{ $sale->estimate->customer->email }}</p>@endunless


                        @if($sale->status!=Utility::STATUS_CLOSED)
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.sales.edit',encrypt($sale->id)) }}" class="btn btn-danger waves-effect waves-light w-sm">
                                <i class="fas fa-pen d-block font-size-12"></i> Edit Proforma
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="col-sm-6 azzet_invoice">
                        <br>
                        <p class="mb-0">Date : {{ $sale->created_at->format('d-m-Y') }}</p>
                        <p class="mb-2">Order ID : {{ $sale->invoice_no }} </p>
                        @unless (empty($sale->estimate->customer->gstin))<p class="mb-2"><b>{!! 'GSTIN/UIN: '. $sale->estimate->customer->gstin !!}</b></p>@endunless
                        State Name :  {{ $sale->estimate->customer->state->name }}, Code : {{ $sale->estimate->customer->state->gst_code }} <br>
                        @unless (empty($sale->estimate->customer->cin))<p class="mb-2">{!! 'CIN: '. $sale->reason !!}</p>@endunless
                        {{-- @unless (empty($sale->reason))<p class="mb-2"><b>Status Notes/Reason: <span class="text-danger"> {{ $sale->reason }}</span></b></p>@endunless --}}
                        <div class="btn-group mt-2" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Proforma Status : <span id="status_id">{{ Utility::saleStatus()[$sale->status]['name'] }}</span> <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <ul id="proforma_status" class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                @foreach (Utility::saleStatus() as $index => $status )
                                    <li><a data-status_id="{{ encrypt($index) }}" href="{{ route('admin.sales.changeStatus') }}" class="dropdown-item status_change">{{ $status['name'] }}</a></li>
                                    {{-- data-plugin="change-status" --}}
                                @endforeach
                            </ul>
                        </div>
                        <div class="btn-group mt-2" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Payment Status : {{ $sale->payment_status }}
                                <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                <li><a class="dropdown-item" href="#allPaymentDetails">Details </a></li>
                            </ul>
                        </div>

                        <div class="mt-4">
                            <a data-plugin="confirm-data" data-confirmtext="Do you really want to download the Invoice?" href="{{ route('admin.sales.download.invoice',encrypt($sale->id)) }}" class="btn btn-primary waves-effect waves-light w-sm">
                                <i class="fas fa-download d-block font-size-12"></i> Download Invoice
                            </a>
                            <a data-plugin="confirm-data" data-confirmtext="Do you really want to print the Invoice?" href="{{ route('admin.sales.view.invoice',encrypt($sale->id)) }}" class="btn btn-secondary waves-effect waves-light w-sm">
                                <i class="fas fa-print d-block font-size-12"></i> Print Invoice
                            </a>
                            @if($sale->status!=Utility::STATUS_CLOSED)
                            <button type="button" id="add_freight" class="btn btn-success waves-effect waves-light w-sm">
                                <i class="fas fa-bus d-block font-size-12"></i> Add Frieght
                            </button>
                            <button type="button" id="add_discount" class="btn btn-danger waves-effect waves-light w-sm">
                                <i class="fas fa-coffee d-block font-size-12"></i> Add Discount
                            </button>
                            <button type="button" id="add_round_off" class="btn btn-info waves-effect waves-light w-sm">
                                <i class="fas fa-bullseye d-block font-size-12"></i> Round Off
                            </button>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="table-responsive">
                        <div class="margin-top">
                            <table cellpadding="0" cellspacing="0"  class="w-full">

                                <tr>
                                    <td class="w-full" colspan="2">
                                        <table class="w-full">
                                            <tr class="center height-20" >
                                                <td class="has-border noright w-3 vertical-m">SI No</td>
                                                <td colspan="3" class="has-border noright w-35 vertical-m">Description of Goods</td>

                                                <td class="has-border noright vertical-m">Quantity</td>
                                                <td class="has-border noright vertical-m">Rate</td>
                                                <td class="has-border noright vertical-m">Per</td>
                                                <td class="has-border vertical-m">Amount</td>
                                            </tr>
                                            <?php $sino = 1; ?>
                                            @foreach($sale->products as $product)
                                            <tr class="center height-20" >
                                                <td class="has-border notop noright nobottom">{{ $sino }}</td>
                                                <td colspan="3" class="has-border notop noright nobottom left-align"><b>{{ $product->name }}</b><br><small>{{ $product->description }}</small></td>
                                                
                                                <td class="has-border notop noright nobottom">{{ $product->pivot->quantity }} {{ $product->uom->name }}</td>
                                                <td class="has-border notop noright nobottom">{{ Utility::formatPrice($product->pivot->price) }}</td>
                                                <td class="has-border notop noright nobottom">{{ $product->uom->name }}</td>
                                                <td class="has-border notop nobottom  right-align"><b>{{ Utility::formatPrice($product->pivot->price*$product->pivot->quantity) }}</b></td>
                                            </tr>

                                            <?php $sino++; ?>
                                            @endforeach
                                            <tr class="center height-20" >
                                                <td class="has-border notop noright nobottom"></td>
                                                <td colspan="3" class="has-border notop noright nobottom right-align"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border nobottom right-align">{{ Utility::formatPrice($sale->sub_total) }}</td>
                                            </tr>
                                            <tr class="center height-20" >
                                                <td class="has-border notop noright nobottom"></td>
                                                <td colspan="3" class="has-border notop noright nobottom right-align"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border nobottom right-align"></td>
                                            </tr>
                                            @unless (($sale->delivery_charge==0))
                                            <tr class="center" >
                                                <td class="has-border notop noright nobottom"></td>
                                                <td colspan="3" class="has-border notop noright nobottom right-align"><b>Freight Outward</b></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop nobottom right-align"><b>{{ Utility::formatPrice($sale->delivery_charge) }}</b></td>
                                            </tr>
                                            @endunless
                                            @if($sale->estimate->customer->state->id==Utility::STATE_ID_KERALA)
                                            <tr class="center" >
                                                <td class="has-border notop noright nobottom"></td>
                                                <td colspan="3" class="has-border notop noright nobottom right-align"><b>CGST</b></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop nobottom right-align"><b>{{ Utility::formatPrice($sale->total_igst/2) }}</b></td>
                                            </tr>
                                            <tr class="center" >
                                                <td class="has-border notop noright nobottom"></td>
                                                <td colspan="3" class="has-border notop noright nobottom right-align"><b>SGST</b></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop nobottom right-align"><b>{{ Utility::formatPrice($sale->total_igst/2) }}</b></td>
                                            </tr>
                                            @else
                                            <tr class="center" >
                                                <td class="has-border notop noright nobottom"></td>
                                                <td colspan="3" class="has-border notop noright nobottom right-align"><b>IGST</b></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop noright nobottom"></td>
                                                <td class="has-border notop nobottom right-align"><b>{{ Utility::formatPrice($sale->total_igst) }}</b></td>
                                            </tr>
                                            @endif
                                            @unless (($sale->discount==0))
                                                <tr class="center" >
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td colspan="3" class="has-border notop nobottom noright right-align"><b>Discount</b></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom right-align"><b>{{ Utility::formatPrice($sale->discount) }}</b></td>
                                                </tr>
                                            @endunless
                                            @unless (($sale->round_off==0))
                                                <tr class="center" >
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td colspan="3" class="has-border notop nobottom noright right-align"><b>Round Off</b></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom noright"></td>
                                                    <td class="has-border notop nobottom right-align"><b>{{ Utility::formatPrice($sale->round_off) }}</b></td>
                                                </tr>
                                            @endunless

                                            <tr class="center height-20" >
                                                <td class="has-border noright"></td>
                                                <td colspan="3" class="has-border noright right-align vertical-m">Total</td>
                                                <td class="has-border noright"></td>
                                                <td class="has-border noright vertical-m">{{ $sale->sub_quantity }} {{ $product->uom->name }}</td>
                                                <td class="has-border noright"></td>
                                                <td class="has-border noright"></td>
                                                <td class="has-border vertical-m right-align"><b>{{ Utility::formatPrice($sale->sub_total+$sale->total_igst+$sale->delivery_charge-$sale->round_off-$sale->discount) }}</b></td>
                                            </tr>

                                            <tr class="center height-20" >
                                                <td colspan="8" class="has-border notop noright left-align"><small>Amount Chargeable (in words)</small><br>
                                                    <b>{{ Utility::CURRENCY_DISPLAY . ' ' . Utility::currencyToWords(($sale->sub_total+$sale->total_igst+$sale->delivery_charge-$sale->round_off-$sale->discount)) }}</b>
                                                </td>
                                                <td class="has-border notop noleft right-align">E. & O.E</td>
                                            </tr>


                                            @if($sale->estimate->customer->state->id==Utility::STATE_ID_KERALA)
                                                <tr class="center height-20" >
                                                    <td rowspan="2" colspan="3" class="has-border notop noright vertical-m w-quarter">HSN/SAC</td>
                                                    <td rowspan="2" class="has-border notop noright vertical-m">Taxable Value</td>
                                                    <td colspan="2" class="has-border notop norigh vertical-m">CGST</td>
                                                    <td colspan="2" class="has-border notop norigh vertical-m">SGST/UTGST</td>
                                                    <td rowspan="2" class="has-border notop vertical-m"><b>Total Tax Amount</b></td>
                                                </tr>
                                                <tr class="center height-20" >
                                                    <td class="has-border notop norigh vertical-m">Rate</td>
                                                    <td class="has-border notop norigh vertical-m">Amount</td>
                                                    <td class="has-border notop norigh vertical-m">Rate</td>
                                                    <td class="has-border notop norigh vertical-m">Amount</td>
                                                </tr>
                                                @foreach($sale->products as $product)
                                                <tr class="center height-20" >
                                                    <td colspan="3" class="has-border notop noright left-align w-quarter">{{ $product->hsn->name }}</td>
                                                    <td class="has-border notop noright">{{ Utility::formatPrice($product->pivot->price*$product->pivot->quantity) }}</td>
                                                    <td class="has-border notop noright">{{ $product->hsn->tax_slab->name/2 }}%</td>
                                                    <td class="has-border notop noright">{{ Utility::formatPrice((($product->pivot->price*$product->pivot->quantity)*($product->hsn->tax_slab->name/100))/2) }}</td>
                                                    <td class="has-border notop noright">{{ $product->hsn->tax_slab->name/2 }}%</td>
                                                    <td class="has-border notop noright">{{ Utility::formatPrice((($product->pivot->price*$product->pivot->quantity)*($product->hsn->tax_slab->name/100))/2) }}</td>
                                                    <td class="has-border notop">{{ Utility::formatPrice(($product->pivot->price*$product->pivot->quantity)*($product->hsn->tax_slab->name/100)) }}</td>
                                                </tr>
                                                @endforeach
                                                <tr class="center height-40" >
                                                    <td colspan="3" class="has-border notop noright right-align  vertical-m w-quarter"><b>Total</b></td>
                                                    <td class="has-border notop noright  vertical-m"><b>{{ Utility::formatPrice($sale->sub_total) }}</b></td>
                                                    <td class="has-border notop noright"></td>
                                                    <td class="has-border notop noright  vertical-m"><b>{{ Utility::formatPrice($sale->total_sgst) }}</b></td>
                                                    <td class="has-border notop noright"></td>
                                                    <td class="has-border notop  vertical-m"><b>{{ Utility::formatPrice($sale->total_sgst) }}</b></td>
                                                    <td class="has-border notop vertical-m"><b>{{ Utility::formatPrice($sale->total_igst) }}</b></td>
                                                </tr>
                                            @else
                                                <tr class="center height-20" >
                                                    <td rowspan="2" colspan="5" class="has-border notop noright vertical-m">HSN/SAC</td>
                                                    <td rowspan="2" class="has-border notop noright vertical-m">Taxable Value</td>
                                                    <td colspan="2" class="has-border notop norigh vertical-m">IGST</td>
                                                    <td rowspan="2" class="has-border notop vertical-m"><b>Total Tax Amount</b></td>
                                                </tr>
                                                <tr class="center height-20" >
                                                    <td class="has-border notop norigh vertical-m">Rate</td>
                                                    <td class="has-border notop noright vertical-m">Amount</td>
                                                </tr>
                                                @foreach($sale->products as $product)
                                                <tr class="center height-20" >
                                                    <td colspan="5" class="has-border notop noright left-align">{{ $product->hsn->name }}</td>
                                                    <td class="has-border notop noright">{{ Utility::formatPrice($product->pivot->price*$product->pivot->quantity) }}</td>
                                                    <td class="has-border notop noright">{{ $product->hsn->tax_slab->name }}%</td>
                                                    <td class="has-border notop noright">{{ Utility::formatPrice(($product->pivot->price*$product->pivot->quantity)*($product->hsn->tax_slab->name/100)) }}</td>
                                                    <td class="has-border notop">{{ Utility::formatPrice(($product->pivot->price*$product->pivot->quantity)*($product->hsn->tax_slab->name/100)) }}</td>
                                                </tr>
                                                @endforeach
                                                <tr class="center height-40" >
                                                    <td colspan="5" class="has-border notop noright right-align  vertical-m"><b>Total</b></td>
                                                    <td class="has-border notop noright  vertical-m"><b>{{ Utility::formatPrice($sale->sub_total) }}</b></td>
                                                    <td class="has-border notop noright"></td>
                                                    <td class="has-border notop noright  vertical-m"><b>{{ Utility::formatPrice($sale->total_igst) }}</b></td>
                                                    <td class="has-border notop  vertical-m"><b>{{ Utility::formatPrice($sale->total_igst) }}</b></td>
                                                </tr>
                                            @endif

                                            <tr class="center height-20" >
                                                <td colspan="9" class="has-border notop left-align"><small>Tax Amount (in words)  : </small>{{ Utility::CURRENCY_DISPLAY . ' ' . Utility::currencyToWords($sale->total_igst)}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="allPaymentDetails" class="card-header">
                <h4 class="card-title">Payment Details</h4>
                <p class="card-title-desc">Total payment of the customer</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="transaction_id">Invoice Amount</label>
                            <input type="text" readonly class="form-control" value="{{ Utility::formatPrice($sale->sub_total+$sale->total_igst+$sale->delivery_charge-$sale->round_off-$sale->discount) }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="transaction_id">Total Paid</label>
                            <input type="text" readonly class="form-control" value="{{ Utility::formatPrice($sale->total_paid) }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="mb-3">
                            <label for="transaction_id">Balance to Pay</label>
                            <input type="text" readonly class="form-control" value="{{ Utility::formatPrice(($sale->sub_total+$sale->total_igst+$sale->delivery_charge-$sale->round_off-$sale->discount) - ($sale->total_paid)) }}">
                        </div>
                    </div>
                </div>
            </div>
            @if($sale->status!=Utility::STATUS_CLOSED)
            {{-- <div class="card-header">
                <h4 class="card-title">Add New Payment</h4>
                <p class="card-title-desc">Add new Payment from the customer</p>
            </div> --}}
            <div class="card-body">
                <form method="POST" action="{{ isset($payment_edit)? route('admin.payments.update') : route('admin.payments.store')  }}">
                    @csrf
                    <input type="hidden" name="sale_id" value="{{ encrypt($sale->id) }}" />
                    @if (!empty($payment_edit))
                        <input type="hidden" name="payment_id" value="{{ encrypt($payment_edit->id) }}" />
                        <input type="hidden" name="_method" value="PUT" />
                    @endif
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label for="amount">Amount Paid</label>
                                <div class="input-group">
                                    <div class="input-group-text">INR</div>
                                    <input type="text" class="form-control" name="amount" id="amount" placeholder="Amount Paid" value="@if (!empty($payment_edit)) {{ $payment_edit->amount }} @endif">
                                    </div>
                                </div>
                            </div>


                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label class="control-label">Payment Mode</label>
                                <select id="payment_method" name="payment_method" class="form-control select2">
                                    {{-- <option value="">Select Payment Mode</option> --}}
                                    @foreach (Utility::paymentMethods() as $index => $payment_method)
                                    <option value="{{ $index }}" @if (!empty($payment_edit)&& ($index==$payment_edit->payment_method)) selected @endif >{{ $payment_method['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('tax_slab_id') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label for="transaction_id">Transaction ID</label>
                                <input id="transaction_id" name="transaction_id" type="text" class="form-control"  placeholder="Transaction ID" value="@if(!empty($payment_edit)) {{ $payment_edit->transaction_id }}@endif">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label for="example-date-input">Payment Date</label>
                                <input id="paid_at" name="paid_at" class="form-control" type="date" value="@if (empty($payment_edit)){{ Carbon\Carbon::parse(now())->format('Y-m-d') }}@else{{ Carbon\Carbon::parse($payment_edit->paid_at)->format('Y-m-d') }}@endif">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" type="text" class="form-control"  placeholder="Description">@if(!empty($payment_edit)) {{ $payment_edit->description }}@endif</textarea>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <label class="control-label">Payment Status</label>
                                <select id="status_p" name="status" class="form-control select2">
                                    {{-- <option value="">Select Payment Status</option> --}}
                                    @foreach (Utility::paymentStatus() as $index_p => $paymentStatus)
                                    <option value="{{ $index_p }}" @if (!empty($payment_edit)&& ($index_p==$payment_edit->status)) selected @endif >{{ $paymentStatus['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('tax_slab_id') <p class="text-danger">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mb-3">
                                <br><button type="submit" class="btn btn-primary waves-effect waves-light">{{ isset($payment_edit) ? 'Update' : 'Save' }}</button>
                                <a href="{{ route('admin.sales.view',encrypt($sale->id)) }}" class="btn btn-secondary waves-effect waves-light">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @endif
            @if($sale->payments()->exists())
                {{-- <div class="card-header">
                    <h4 class="card-title">Payment Details</h4>
                    <p class="card-title-desc">All Payment Details of the customer</p>
                </div> --}}
                <div class="card-body">
                    <div class="row">
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane customerdetailsTab active" role="tabpanel">
                                <div class="table-responsive mb-4">
                                    <table class="table align-middle dt-responsive table-check nowrap" style="border-collapse: collapse; border-spacing: 0 8px; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th scope="col">Date</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Mode</th>
                                            <th scope="col">Transaction ID</th>
                                            <th scope="col">status</th>
                                            <th scope="col">Description</th>
                                            <th style="width: 80px; min-width: 80px;">Edit</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($sale->payments as $payment)
                                            <tr>
                                                    <td>
                                                        {{ $payment->paid_at->format('d M, Y') }}
                                                    </td>
                                                    <td>
                                                    <a href="#" class="text-body">{{ Utility::CURRENCY_DISPLAY . ' ' . Utility::formatPrice($payment->amount) }}</a>
                                                    </td>

                                                <td>{{ Utility::paymentMethods()[$payment->payment_method]['name'] }}</td>
                                                <td>{{ $payment->transaction_id }}</td>
                                                <td>{{ Utility::paymentStatus()[$payment->status]['name'] }}</td>
                                                <td>
                                                    {{ $payment->description }}
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-link font-size-16 shadow-none py-0 text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bx bx-dots-horizontal-rounded"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item" href="{{ route('admin.sales.view',encrypt($sale->id). '?payment_edit_id=' . encrypt($payment->id).'#allPaymentDetails') }}"><i class="mdi mdi-pencil font-size-16 text-success me-1"></i> Edit</a></li>
                                                                <li><a href="#" class="dropdown-item" data-plugin="delete-data" data-target-form="#form_delete_{{ $loop->iteration }}"><i class="mdi mdi-trash-can font-size-16 text-danger me-1"></i> Delete</a></li>
                                                                <form id="form_delete_{{ $loop->iteration }}" method="POST" action="{{ route('admin.payments.destroy',encrypt($payment->id))}}">
                                                                    @csrf
                                                                    <input type="hidden" name="_method" value="DELETE" />
                                                                    <input type="hidden" name="sale_id" id="sale_del_id" value="{{ encrypt($sale->id) }}" />
                                                                </form>
                                                            </ul>
                                                        </div>
                                                    </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <!-- end table -->
                                    {{-- <div class="pagination justify-content-center">{{ $sale->payments->links() }}</div> --}}
                                </div>
                                <!-- end table responsive -->

                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>


    </div>
</div>
<!-- end row -->
@endsection

@section('css')
<link href="{{ URL::asset('assets/css/invoice.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('script')
<script src="{{ URL::asset('assets/libs/datatables.net/datatables.net.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-bs4/datatables.net-bs4.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/datatables.net-responsive/datatables.net-responsive.min.js') }}"></script>

<script src="{{ URL::asset('assets/js/pages/datatable-pages.init.js') }}"></script>
<script>

    $(document).ready(function() {
        $('#proforma_status .status_change').on('click', function(e) {
            e.preventDefault();
            var targetUrl = $(this).attr('href');
            var status_id = $(this).data('status_id');
            // SweetAlert2 popup with input fields
            Swal.fire({
                title: 'Enter Note/Reason',
                html:
                    '<input type="hidden" id="sale_id_s" class="form-control" value="{{ encrypt($sale->id) }}">' +
                    '<input type="text" id="description_s" class="form-control" value="" placeholder="Enter Note/Reason, if have"><br>' +
                    '<input type="hidden" id="status_id_s" class="form-control" value="' + status_id + '">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Submit',
                preConfirm: () => {
                    const status_id_s = document.getElementById('status_id_s').value;
                    const sale_id_s = document.getElementById('sale_id_s').value;
                    const description_s = document.getElementById('description_s').value;

                    // Check if the inputs are valid
                    if (!sale_id_s) {
                        Swal.showValidationMessage('Something Went wrong!');
                        return false;
                    }
                    return { status_id_s: status_id_s, sale_id_s: sale_id_s, description_s:description_s };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get input values from the SweetAlert2 popup
                    const status_id_s = result.value.status_id_s;
                    const sale_id_s = result.value.sale_id_s;
                    const description_s = result.value.description_s;

                    // Send the data using AJAX
                    $.ajax({
                        url: targetUrl,
                        type: 'POST',
                        data: { status_id_s: status_id_s, sale_id_s: sale_id_s, description_s:description_s },
                        success: function(response) {
                            // console.log(response);
                            Swal.fire(
                                'Success!',
                                'Your data has been submitted.',
                                'success'
                            ).then((result) => {

                                refreshPage();
                            });
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'There was a problem with the submission.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $('#add_freight').on('click', function() {
            // SweetAlert2 popup with input fields
            Swal.fire({
                title: 'Add Your Delivery Charge',
                html:
                    '<input type="text" id="delivery_charge" class="form-control" value="{{ Utility::formatPrice($sale->delivery_charge) }}" placeholder="Name"><br>' +
                    '<input type="hidden" id="sale_id" class="form-control" value="{{ encrypt($sale->id) }}">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Submit',
                preConfirm: () => {
                    const delivery_charge = document.getElementById('delivery_charge').value;
                    const sale_id = document.getElementById('sale_id').value;

                    // Check if the inputs are valid
                    if (!delivery_charge) {
                        Swal.showValidationMessage('Please Enter Delivery charge');
                        return false;
                    }
                    return { delivery_charge: delivery_charge, sale_id: sale_id };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get input values from the SweetAlert2 popup
                    const delivery_charge = result.value.delivery_charge;
                    const sale_id = result.value.sale_id;

                    // Send the data using AJAX
                    $.ajax({
                        url: '{{ route("admin.sales.addFreight") }}',
                        type: 'POST',
                        data: { delivery_charge: delivery_charge, sale_id: sale_id },
                        success: function(response) {
                            // console.log(response);
                            Swal.fire(
                                'Success!',
                                'Your data has been submitted.',
                                'success'
                            ).then((result) => {
                                refreshPage();

                            });
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'There was a problem with the submission.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $('#add_discount').on('click', function() {
            // SweetAlert2 popup with input fields
            Swal.fire({
                title: 'Add Your Discount',
                html:
                    '<input type="text" id="discount" class="form-control" value="{{ Utility::formatPrice($sale->discount) }}" placeholder="Name"><br>' +
                    '<input type="hidden" id="sale_id" class="form-control" value="{{ encrypt($sale->id) }}">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Submit',
                preConfirm: () => {
                    const discount = document.getElementById('discount').value;
                    const sale_id = document.getElementById('sale_id').value;

                    // Check if the inputs are valid
                    if (!discount) {
                        Swal.showValidationMessage('Please Enter Discount Amount');
                        return false;
                    }
                    return { discount: discount, sale_id: sale_id };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get input values from the SweetAlert2 popup
                    const discount = result.value.discount;
                    const sale_id = result.value.sale_id;

                    // Send the data using AJAX
                    $.ajax({
                        url: '{{ route("admin.sales.addDiscount") }}',
                        type: 'POST',
                        data: { discount: discount, sale_id: sale_id },
                        success: function(response) {
                            Swal.fire(
                                'Success!',
                                'Your data has been submitted.',
                                'success'
                            ).then((result) => {
                                refreshPage();
                            });
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'There was a problem with the submission.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $('#add_round_off').on('click', function() {
            // SweetAlert2 popup with input fields
            Swal.fire({
                title: 'Round Off',
                html:
                    '<input type="text" id="round_off" class="form-control" value="{{ Utility::formatPrice($sale->round_off) }}" placeholder="Name"><br>' +
                    '<input type="hidden" id="sale_id" class="form-control" value="{{ encrypt($sale->id) }}">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Submit',
                preConfirm: () => {
                    const round_off = document.getElementById('round_off').value;
                    const sale_id = document.getElementById('sale_id').value;

                    // Check if the inputs are valid
                    if (!round_off) {
                        Swal.showValidationMessage('Please Enter Discount Amount');
                        return false;
                    }
                    return { round_off: round_off, sale_id: sale_id };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get input values from the SweetAlert2 popup
                    const round_off = result.value.round_off;
                    const sale_id = result.value.sale_id;

                    // Send the data using AJAX
                    $.ajax({
                        url: '{{ route("admin.sales.addRoundOff") }}',
                        type: 'POST',
                        data: { round_off: round_off, sale_id: sale_id },
                        success: function(response) {
                            Swal.fire(
                                'Success!',
                                'Your data has been submitted.',
                                'success'
                            ).then((result) => {
                                refreshPage();
                            });
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'There was a problem with the submission.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // $(document).on('click','[data-plugin="change-status"]',function(e) {
        //     e.preventDefault();
        //     if (!confirm('Do you want to change the status?')) return;
        //     var url = $(this).attr('href');
        //     $.ajax({
        //         type: "GET",
        //         url: url,
        //         success: function (data) {
        //             refreshPage();
        //         }
        //     });
	    // });

    });

</script>
{{--  --}}
@endsection
