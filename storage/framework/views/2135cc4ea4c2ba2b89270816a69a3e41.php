<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Add_Estimate'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/select2/select2.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/dropzone/dropzone.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Proforma_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Estimate_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> <?php echo app('translator')->get('translation.Edit_Estimate'); ?> & <?php echo app('translator')->get('translation.Edit_Proforma'); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <form method="POST" action="<?php echo e(route('admin.sales.update')); ?>" >
        <?php echo csrf_field(); ?>
        <?php if(isset($estimate)): ?>
            <input type="hidden" name="estimate_id" value="<?php echo e(encrypt($estimate->id)); ?>" />
            <input type="hidden" name="_method" value="PUT" />
        <?php endif; ?>

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
                                    <label class="control-label"><?php echo app('translator')->get('translation.Customer'); ?></label>
                                    <select id="customer_id" name="customer_id" class="form-control select2">
                                        <option value="">Select <?php echo app('translator')->get('translation.Customer'); ?></option>
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer->id); ?>" <?php echo e($customer->id==$estimate->customer->id ? 'selected':''); ?> ><?php echo e($customer->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <p><a href="<?php echo e(route('admin.customers.create')); ?>"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;New <?php echo app('translator')->get('translation.Customer'); ?></a></p>
                            </div>


                        </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Products</h4>
                    <p class="card-title-desc"><?php echo e(isset($estimate)? 'Edit' : "Add"); ?> details of Products</p>
                </div>
                <div class="card-body" id="product_container">
                    <?php $__currentLoopData = $estimate->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $estimate_product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row close_container" id="close_container_<?php echo e($index); ?>" style="background: rgb(236, 236, 234); margin:5px;  margin-bottom:20px; padding:20px;">

                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="control-label">Product</label>
                                        <select id="products-<?php echo e($index); ?>" name="products[<?php echo e($index); ?>]" class="form-control select2" onChange="getProductDetail(this.value,<?php echo e($index); ?>);">
                                            <option value="">Select Product</option>
                                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($product->id); ?>" <?php echo e($product->id==$estimate_product->id ? 'selected':''); ?>><?php echo e($product->name); ?> </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="mb-3">
                                        <label>Quantity</label>
                                        <input id="quantities-<?php echo e($index); ?>" name="quantities[<?php echo e($index); ?>]" type="text" class="form-control"  placeholder="Quantity" value="<?php echo e($estimate_product->pivot->quantity); ?>">
                                    </div>
                                </div>

                                <a class="btn-close" data-target="#close_container_<?php echo e($index); ?>" style="font-size: 18px; padding-top:0;"><i class="fa fa-trash"></i></a>

                                <div id="product_detail-<?php echo e($index); ?>" class="col-sm-12">
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
                                                <input id="profits-<?php echo e($index); ?>" name="profits[<?php echo e($index); ?>]" type="text" class="form-control"  placeholder="Profit" value="<?php echo e($estimate_product->pivot->profit); ?>">
                                            </div>
                                        </div>
                                        <div id="component_container_<?php echo e($index); ?>">
                                        <?php $__currentLoopData = $estimate_product->components; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index_comp => $prod_component): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="row close_container" id="component_close_container_<?php echo e($index.'_'.$index_comp); ?>">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="control-label">Component</label>
                                                    <select id="component_names-<?php echo e($index.'_'.$index_comp); ?>" name="component_names[<?php echo e($index); ?>][<?php echo e($index_comp); ?>]" class="form-control component_names" onChange="">
                                                        <option value="">Select Component</option>
                                                        <?php $__currentLoopData = $components; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $component): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($component->id); ?>" <?php echo e($component->id==$prod_component->component_id?'selected':''); ?>><?php echo e($component->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="mb-3">
                                                    <label>Cost</label>
                                                    <input id="costs-<?php echo e($index.'_'.$index_comp); ?>" name="costs[<?php echo e($index); ?>][<?php echo e($index_comp); ?>]" type="text" class="form-control costs"  placeholder="Cost" value="<?php echo e($prod_component->cost); ?>">
                                                    <input id="o_costs-<?php echo e($index.'_'.$index_comp); ?>" name="o_costs[<?php echo e($index); ?>][<?php echo e($index_comp); ?>]" type="hidden" class="o_costs" value="<?php echo e($prod_component->o_cost); ?>">
                                                </div>
                                            </div>
                                            <a class="btn-close" data-target="#component_close_container_<?php echo e($index.'_'.$index_comp); ?>"><i class="fa fa-trash"></i></a>

                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>

                                        <div class="p-4 pt-1">
                                            <a href="#" data-toggle="add-more-component" data-template="#template_component"
                                            data-close=".wb-close" data-container="#component_container_<?php echo e($index); ?>"
                                            data-position="<?php echo e($index); ?>"
                                            data-count="<?php echo e(isset($estimate_product->components) ? $estimate_product->components->count()-1 : 0); ?>"
                                            data-addindex='[{"selector":".component_names","attr":"name", "value":"component_names"},{"selector":".costs","attr":"name", "value":"costs"},{"selector":".o_costs","attr":"name", "value":"o_costs"}]'
                                            data-plugins='[{"selector":".component_names","plugin":"select2"}]'
                                            data-onchanges='[{"selector":".component_names","attr":"onChange"}]'
                                            data-increment='[{"selector":".component_names","attr":"id", "value":"component_names"},{"selector":".costs","attr":"id", "value":"costs"},{"selector":".o_costs","attr":"id", "value":"o_costs"}]'><i
                                            class="fa fa-plus-circle"></i>&nbsp;&nbsp;New Component</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
                <div class="p-4 pt-1">
                    <a href="#" data-toggle="add-more" data-template="#template_product"
                    data-close=".wb-close" data-container="#product_container"
                    data-count="<?php echo e(isset($estimate) ? $estimate->products->count()-1 : 0); ?>"
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
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <?php $__currentLoopData = $components; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $component): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($component->id); ?>"><?php echo e($component->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <a href="<?php echo e(route('admin.sales.edit',encrypt($estimate->sale->id))); ?>" class="btn btn-secondary waves-effect waves-light">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- end row -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('assets/libs/select2/select2.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/libs/dropzone/dropzone.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('assets/js/pages/ecommerce-select2.init.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<script>

    function getProductDetail(val, position) {
        var formData = {'product_id' : val, 'position':position};
        $.ajax({
            type: "POST",
            url: "<?php echo e(route('admin.estimates.get_product_detail')); ?>",
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
            url: "<?php echo e(route('admin.products.get_cost')); ?>",
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\sales\edit.blade.php ENDPATH**/ ?>