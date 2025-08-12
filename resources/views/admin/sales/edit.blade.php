@extends('admin.layouts.master')
@section('title') @lang('translation.Add_Estimate') @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet">
@endsection
@section('content')
@component('admin.dir_components.breadcrumb')
@slot('li_1') @lang('translation.Proforma_Manage') @endslot
@slot('li_2') @lang('translation.Estimate_Manage') @endslot
@slot('title') @lang('translation.Edit_Estimate') & @lang('translation.Edit_Proforma') @endslot
@endcomponent

<div class="row">
    <form method="POST" action="{{ route('admin.sales.update') }}" >
        @csrf
        @if (isset($estimate))
            <input type="hidden" name="estimate_id" value="{{ encrypt($estimate->id) }}" />
            <input type="hidden" name="_method" value="PUT" />
        @endif

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Estimate Details</h4>
                    <p class="card-title-desc">Edit the Details of Estimates</p>
                </div>
                <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label class="control-label">@lang('translation.Customer')</label>
                                    <select id="customer_id" name="customer_id" class="form-control select2">
                                        <option value="">Select @lang('translation.Customer')</option>
                                        @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $customer->id==$estimate->customer->id ? 'selected':'' }} >{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p><a href="{{ route('admin.customers.create') }}"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;New @lang('translation.Customer')</a></p>
                            </div>


                        </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Products</h4>
                    <p class="card-title-desc">{{ isset($estimate)? 'Edit' : "Add" }} details of Products</p>
                </div>
                <div class="card-body" id="product_container">
                    @foreach ($estimate->products as $index => $estimate_product)
                            <div class="row close_container" id="close_container_{{ $index }}" style="background: rgb(236, 236, 234); margin:5px;  margin-bottom:20px; padding:20px;">

                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="control-label">Product</label>
                                        <select id="products-{{ $index }}" name="products[{{ $index }}]" class="form-control select2" onChange="getProductDetail(this.value,{{ $index }});">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" {{ $product->id==$estimate_product->id ? 'selected':'' }}>{{ $product->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="mb-3">
                                        <label>Quantity</label>
                                        <input id="quantities-{{ $index }}" name="quantities[{{ $index }}]" type="text" class="form-control"  placeholder="Quantity" value="{{ $estimate_product->pivot->quantity }}">
                                    </div>
                                </div>

                                <a class="btn-close" data-target="#close_container_{{ $index }}" style="font-size: 18px; padding-top:0;"><i class="fa fa-trash"></i></a>

                                <div id="product_detail-{{ $index }}" class="col-sm-12">
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <select class="form-control select2" >
                                                        <option value="">Profit</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="mb-3">
                                                <input id="profits-{{ $index }}" name="profits[{{ $index }}]" type="text" class="form-control"  placeholder="Profit" value="{{ $estimate_product->pivot->profit }}">
                                            </div>
                                        </div>
                                        <div id="component_container_{{ $index }}">
                                        @foreach($estimate_product->components as $index_comp => $prod_component)
                                        <div class="row close_container" id="component_close_container_{{ $index.'_'.$index_comp }}">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="control-label">Component</label>
                                                    <select id="component_names-{{ $index.'_'.$index_comp }}" name="component_names[{{ $index }}][{{ $index_comp }}]" class="form-control component_names" onChange="">
                                                        <option value="">Select Component</option>
                                                        @foreach ($components as $component)
                                                            <option value="{{ $component->id }}" {{ $component->id==$prod_component->component_id?'selected':'' }}>{{ $component->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="mb-3">
                                                    <label>Cost</label>
                                                    <input id="costs-{{ $index.'_'.$index_comp }}" name="costs[{{ $index }}][{{ $index_comp }}]" type="text" class="form-control costs"  placeholder="Cost" value="{{ $prod_component->cost }}">
                                                    <input id="o_costs-{{ $index.'_'.$index_comp }}" name="o_costs[{{ $index }}][{{ $index_comp }}]" type="hidden" class="o_costs" value="{{ $prod_component->o_cost }}">
                                                </div>
                                            </div>
                                            <a class="btn-close" data-target="#component_close_container_{{ $index.'_'.$index_comp }}"><i class="fa fa-trash"></i></a>

                                        </div>
                                        @endforeach
                                        </div>

                                        <div class="p-4 pt-1">
                                            <a href="#" data-toggle="add-more-component" data-template="#template_component"
                                            data-close=".wb-close" data-container="#component_container_{{ $index }}"
                                            data-position="{{ $index }}"
                                            data-count="{{ isset($estimate_product->components) ? $estimate_product->components->count()-1 : 0 }}"
                                            data-addindex='[{"selector":".component_names","attr":"name", "value":"component_names"},{"selector":".costs","attr":"name", "value":"costs"},{"selector":".o_costs","attr":"name", "value":"o_costs"}]'
                                            data-plugins='[{"selector":".component_names","plugin":"select2"}]'
                                            data-onchanges='[{"selector":".component_names","attr":"onChange"}]'
                                            data-increment='[{"selector":".component_names","attr":"id", "value":"component_names"},{"selector":".costs","attr":"id", "value":"costs"},{"selector":".o_costs","attr":"id", "value":"o_costs"}]'><i
                                            class="fa fa-plus-circle"></i>&nbsp;&nbsp;New Component</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                </div>
                <div class="p-4 pt-1">
                    <a href="#" data-toggle="add-more" data-template="#template_product"
                    data-close=".wb-close" data-container="#product_container"
                    data-count="{{ isset($estimate) ? $estimate->products->count()-1 : 0 }}"
                    data-addindex='[{"selector":".products","attr":"name", "value":"products"},{"selector":".quantities","attr":"name", "value":"quantities"}]'
                    data-plugins='[{"selector":".products","plugin":"select2"}]'
                    data-onchanges='[{"selector":".products","attr":"onChange"}]'
                    data-increment='[{"selector":".products","attr":"id", "value":"products"},{"selector":".quantities","attr":"id", "value":"quantities"},{"selector":".product_detail","attr":"id", "value":"product_detail"}]'><i
                                class="fa fa-plus-circle"></i>&nbsp;&nbsp;Add Item</a>
                </div>
            </div>


            <div class="row hidden" id="template_product" style="background: rgb(236, 236, 234); margin:5px; margin-bottom:20px; padding:20px;">

                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="control-label">Product</label>
                        <select id="" name="" class="form-control products" onChange="">
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="mb-3">
                        <label>Quantity</label>
                        <input id="" name="" type="text" class="form-control quantities"  placeholder="Quantity" value="">
                    </div>
                </div>
                <div id="" class="col-sm-12 product_detail">

                </div>
            </div>


            <div class="row hidden" id="template_component">

                <div class="col-sm-6">

                    <div class="mb-3">
                        <label class="control-label">Component</label>
                        <select id="" name="" class="form-control component_names" onChange="">
                            <option value="">Select Component</option>
                            @foreach ($components as $component)
                                <option value="{{ $component->id }}">{{ $component->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="mb-3">
                        <label>Cost</label>
                        <input id="" name="" type="text" class="form-control costs"  placeholder="Cost" value="">
                        <input id="" name="" type="hidden" class="o_costs" value="">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Update Estimate & Proforma</button>
                        <a href="{{ route('admin.sales.edit',encrypt($estimate->sale->id)) }}" class="btn btn-secondary waves-effect waves-light">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- end row -->
@endsection
@section('script')
<script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/dropzone/dropzone.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/ecommerce-select2.init.js') }}"></script>

<script>

    function getProductDetail(val, position) {
        var formData = {'product_id' : val, 'position':position};
        $.ajax({
            type: "POST",
            url: "{{ route('admin.estimates.get_product_detail') }}",
            data: formData,
            success: function(data){
                $("#product_detail-"+position).html(data);
            }
        });
    }

    function getcost(val, position,position2) {
        var formData = {'component_id' : val, 'position':position};
        $.ajax({
            type: "POST",
            url: "{{ route('admin.products.get_cost') }}",
            data: formData,
            success: function(data){
                $("#costs-"+position+"_"+position2).val(data);
                $("#o_costs-"+position+"_"+position2).val(data);
            }
        });

    }
</script>
<script>
    $(document).ready(function() {
        // $('.select2_products').select2();
        $(document).on("click", 'a[data-toggle="add-more"]', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var $el = $($(this).attr("data-template")).clone();
            $el.removeClass("hidden");
            $el.attr("id", "");

            var count = $(this).data('count');
            count = typeof count == "undefined" ? 0 : count;
            count = count + 1;
            $(this).data('count', count);

            var addindex = $(this).data("addindex");
            if(typeof addindex == "object") {
                $.each(addindex, function(i, p) {
                    var have_child = p.have_child;
                    if(typeof(have_child)  === "undefined") {
                        $el.find(p.selector).attr(p.attr, p.value + '[' + count + ']');
                    }else {
                        $el.find(p.selector).attr(p.attr, p.value +'['+count+']'+'['+have_child+']' );
                    }
                });
            }

            var increment = $(this).data("increment");
            if(typeof increment == "object") {
                $.each(increment, function(i, p) {
                    var have_child = p.have_child;
                    if(typeof(have_child)  === "undefined") {
                        $el.find(p.selector).attr(p.attr, p.value +"-"+count);
                    }else {
                        $el.find(p.selector).attr(p.attr, p.value +"-"+count+"-"+have_child);
                    }
                });
            }

            var plugins = $(this).data("plugins");
            if(typeof increment == "object") {
                $.each(plugins, function(i, p) {
                if(p.plugin=='select2') {
                    $el.find(p.selector).select2();
                }

            });
            }

            var onchanges = $(this).data("onchanges");
            if(typeof onchanges == "object") {
                $.each(onchanges, function(i, p) {
                    $el.find(p.selector).attr(p.attr, "getProductDetail(this.value," + count + ")");
            });
            }

            $el.hide().appendTo($(this).attr("data-container")).fadeIn();

        });

        $(document).on("click", 'a[data-toggle="add-more-component"]', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var $el = $($(this).attr("data-template")).clone();
            $el.removeClass("hidden");
            $el.attr("id", "");

            var count = $(this).data('count');
            count = typeof count == "undefined" ? 0 : count;
            count = count + 1;
            $(this).data('count', count);

            var position = $(this).data('position');
            position = typeof position == "undefined" ? 0 : position;


            var addindex = $(this).data("addindex");
            if(typeof addindex == "object") {
                $.each(addindex, function(i, p) {
                    var have_child = p.have_child;
                    // if(typeof(have_child)  === "undefined") {
                        // $el.find(p.selector).attr(p.attr, p.value + '[' + count + ']');
                        $el.find(p.selector).attr(p.attr, p.value +'['+position+']'+'['+count+']' );
                    // }else {

                    // }
                });
            }

            var increment = $(this).data("increment");
            if(typeof increment == "object") {
                $.each(increment, function(i, p) {
                    var have_child = p.have_child;
                    // if(typeof(have_child)  === "undefined") {
                        // $el.find(p.selector).attr(p.attr, p.value +"-"+count);
                        $el.find(p.selector).attr(p.attr, p.value +"-"+position+"_"+count);
                    // }else {

                    // }
                });
            }

            var plugins = $(this).data("plugins");
            if(typeof increment == "object") {
                $.each(plugins, function(i, p) {
                if(p.plugin=='select2') {
                    $el.find(p.selector).select2();
                }

            });
            }

            var onchanges = $(this).data("onchanges");
            if(typeof onchanges == "object") {
                $.each(onchanges, function(i, p) {
                    $el.find(p.selector).attr(p.attr, "getcost(this.value," + position + "," + count + ")");
            });
            }

            $el.hide().appendTo($(this).attr("data-container")).fadeIn();

        });

    })
</script>
@endsection
