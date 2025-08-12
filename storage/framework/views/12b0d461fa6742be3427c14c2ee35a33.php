<?php $__env->startSection('title'); ?> <?php if(isset($product)): ?> <?php echo app('translator')->get('translation.Edit_Product'); ?> <?php else: ?> <?php echo app('translator')->get('translation.Add_Product'); ?> <?php endif; ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/select2/select2.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/dropzone/dropzone.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Catalogue_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Product_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> <?php if(isset($product)): ?> <?php echo app('translator')->get('translation.Edit_Product'); ?> <?php else: ?> <?php echo app('translator')->get('translation.Add_Product'); ?> <?php endif; ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<?php if(isset($product) && !$product->is_approved ): ?>
<div class="alert alert-warning alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-warning"></i><strong>Warning</strong> - This Product is yet to approve !!
</div>
<?php endif; ?>
<div class="row">
    <form method="POST" action="<?php echo e(isset($product)? route('admin.products.update') : route('admin.products.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if(isset($product)): ?>
            <input type="hidden" name="product_id" value="<?php echo e(encrypt($product->id)); ?>" />
            <input type="hidden" name="_method" value="PUT" />
        <?php endif; ?>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Product Details</h4>
                    <p class="card-title-desc"><?php echo e(isset($product)? 'Edit' : "Enter"); ?> the Details of your Products</p>
                </div>
                <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="control-label">Category</label>
                                    <select id="category_id" name="category_id" class="form-control select2">
                                        <option value="">Select Category</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>" <?php if(isset($product)): ?> <?php echo e($category->id==$product->category->id ? 'selected':''); ?> <?php endif; ?>><?php echo e($category->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input id="name" name="name" type="text" class="form-control"  placeholder="Name" value="<?php echo e(isset($product)?$product->name:old('name')); ?>">
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="mb-3">
                                    <label for="code">Product Code</label>
                                    <input id="code" name="code" type="text" class="form-control"  placeholder="Product Code" value="<?php echo e(isset($product)?$product->code:old('code')); ?>">
                                    <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="profit">Profit</label>
                                    <input id="profit" name="profit" type="text" class="form-control"  placeholder="Profit" value="<?php echo e(isset($product)?$product->profit:old('profit')); ?>">
                                    <?php $__errorArgs = ['profit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="mb-3">
                                    <label class="control-label">Image</label>
                                    <span id="imageContainer" <?php if(isset($product)&&empty($product->image)): ?> style="display: none" <?php endif; ?>>
                                        <?php if(isset($product)&&!empty($product->image)): ?>
                                            <img src="<?php echo e(URL::asset(App\Models\Category::DIR_STORAGE . $product->image)); ?>" alt="" class="avatar-xxl rounded-circle me-2">
                                            <button type="button" class="btn-close" aria-label="Close">x</button>
                                        <?php endif; ?>
                                    </span>

                                    <span id="fileContainer" <?php if(isset($product)&&!empty($product->image)): ?> style="display: none" <?php endif; ?>>
                                        <div class="d-flex align-items-center gap-2">
                                        <input id="image" name="image" type="file" class="form-control"  placeholder="File">
                                        <?php if(isset($product)&&!empty($product->image)): ?>
                                            <button type="button" class="btn-close" aria-label="Close">x</button>
                                        <?php endif; ?>
                                        </div>
                                    </span>
                                    <input name="isImageDelete" type="hidden" value="0">
                                </div>
                            </div>



                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Notes</label>
                                    <textarea class="form-control" rows="2" placeholder="Enter notes, if any" id="description" name="description"><?php echo e(isset($product)?$product->description:old('description ')); ?></textarea>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Components</h4>
                    <p class="card-title-desc"><?php echo e(isset($product)? 'Edit' : "Add"); ?> details of components</p>
                </div>
                <div class="card-body" id="component_container">
                    <?php if(isset($product)): ?>
                        <?php $__currentLoopData = $product->components; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product_component): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row close_container" id="close_container_<?php echo e($index); ?>">

                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label class="control-label">Component Name</label>
                                        <select id="component_names-<?php echo e($index); ?>" name="component_names[<?php echo e($index); ?>]" class="form-control select2" onChange="getcost(this.value,<?php echo e($index); ?>);">
                                            <option value="">Select Component</option>
                                            <?php $__currentLoopData = $components; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $component): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($component->id); ?>" <?php echo e($component->id==$product_component->id ? 'selected':''); ?>><?php echo e($component->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="mb-3">
                                        <label>Cost</label>
                                        <input id="costs-<?php echo e($index); ?>" name="costs[<?php echo e($index); ?>]" type="text" class="form-control"  placeholder="Cost" value="<?php echo e($product_component->pivot->cost); ?>">
                                        <input id="o_costs-<?php echo e($index); ?>" name="o_costs[<?php echo e($index); ?>]" type="hidden" value="<?php echo e($product_component->pivot->o_cost); ?>">
                                    </div>
                                </div>
                                <a class="btn-close" data-target="#close_container_<?php echo e($index); ?>"><i class="fa fa-trash"></i></a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php endif; ?>
                    <?php if(empty($product)): ?>
                        <div class="row">


                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="control-label">Component Name</label>
                                    <select id="component_names-0" name="component_names[0]" class="form-control select2" onChange="getcost(this.value,0);" >
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
                                    <input id="costs-0" name="costs[0]" type="text" class="form-control"  placeholder="Cost" value="">
                                    <input id="o_costs-0" name="o_costs[0]" type="hidden" value="">
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="p-4 pt-1">
                    <a href="#" data-toggle="add-more" data-template="#template_component"
                    data-close=".wb-close" data-container="#component_container"
                    data-count="<?php echo e(isset($product) ? $product->components->count()-1 : 0); ?>"
                    data-addindex='[{"selector":".component_names","attr":"name", "value":"component_names"},{"selector":".costs","attr":"name", "value":"costs"},{"selector":".o_costs","attr":"name", "value":"o_costs"}]'
                    data-plugins='[{"selector":".component_names","plugin":"select2"}]'
                    data-onchanges='[{"selector":".component_names","attr":"onChange"}]'
                    data-increment='[{"selector":".component_names","attr":"id", "value":"component_names"},{"selector":".costs","attr":"id", "value":"costs"},{"selector":".o_costs","attr":"id", "value":"o_costs"}]'><i
                                class="fa fa-plus-circle"></i>&nbsp;&nbsp;New Component</a>
                </div>
            </div>


            <div class="row hidden" id="template_component">

                <div class="col-sm-6">
                    <div class="mb-3">
                        <label class="control-label">Component Name</label>
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
                    <h4 class="card-title">Paper Details</h4>
                    <p class="card-title-desc"><?php echo e(isset($product)? 'Edit' : "Enter"); ?> the Details of Paper</p>
                </div>
                <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="control-label">Handle Type</label>
                                    <select id="handle_type" name="handle_type" class="form-control select2">
                                        <option value="0">Select Handle Type</option>
                                        <?php $__currentLoopData = Utility::handleTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $handle_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($index); ?>" <?php if(isset($product)): ?> <?php echo e($index==$product->handle_type ? 'selected':''); ?> <?php endif; ?>><?php echo e($handle_type); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['handle_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="mb-3">
                                    <label for="width">Bag Width</label>
                                    <input id="width" name="width" type="text" class="form-control"  placeholder="Bag Width" value="<?php echo e(isset($product)?$product->width:old('width')); ?>">
                                    <?php $__errorArgs = ['width'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>


                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="height">Bag Height</label>
                                    <input id="height" name="height" type="text" class="form-control"  placeholder="Bag Height" value="<?php echo e(isset($product)?$product->height:old('height')); ?>">
                                    <?php $__errorArgs = ['height'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>


                                <div class="mb-3">
                                    <label for="gusset">Bag Gusset</label>
                                    <input id="gusset" name="gusset" type="text" class="form-control"  placeholder="Bag Gusset" value="<?php echo e(isset($product)?$product->gusset:old('gusset')); ?>">
                                    <?php $__errorArgs = ['gusset'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="paper_width"><b>Paper Width</b></label>
                                        <?php if(isset($product)): ?>
                                            <?php
                                                $paper_width = (($product->width+$product->gusset)*2)+2;
                                                $formattedWidth = sprintf("%.2f", $paper_width);

                                                $paper_height_f = ($product->height+($product->gusset)/2)+2;
                                                $paper_height = $product->handle_type==2?$paper_height_f+3:$paper_height_f;
                                                $formattedHeight = sprintf("%.2f", $paper_height);
                                            ?>
                                        <?php else: ?>
                                            <?php
                                                $formattedWidth = '';
                                                $formattedHeight = '';
                                            ?>
                                        <?php endif; ?>
                                    <input id="paper_width" name="paper_width" type="text" class="form-control" value="<?php echo e($formattedWidth); ?>" readonly>
                                    <?php $__errorArgs = ['paper_width'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label for="paper_height"><b>Paper Height</b></label>
                                    <input id="paper_height" name="paper_height" type="text" class="form-control" value="<?php echo e($formattedHeight); ?>" readonly>
                                    <?php $__errorArgs = ['paper_height'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>


                        </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light"><?php echo e(isset($product) ? $product->is_approved?'Update':'Update & Approve' : 'Save'); ?></button>
                        <button type="reset" class="btn btn-secondary waves-effect waves-light">Cancel</button>
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

<script>
    $(document).ready(function() {
        $('#imageContainer').find('button').click(function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to delete?')) return;
            $('#imageContainer').hide();
            $('#fileContainer').show();
            $('input[name="isImageDelete"]').val(1);
        })

        $('#fileContainer').find('button').click(function(e) {
            e.preventDefault();
            $('#fileContainer').hide();
            $('#imageContainer').show();
            $('input[name="isImageDelete"]').val(0);
        })
    });
</script>

<script>

    function getcost(val, position) {
        var formData = {'component_id' : val, 'position':position};
        $.ajax({
            type: "POST",
            url: "<?php echo e(route('admin.products.get_cost')); ?>",
            data: formData,
            success: function(data){
                $("#costs-"+position).val(data);
                $("#o_costs-"+position).val(data);
            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        // $('.select2_component_names').select2();
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
                    // if(typeof(have_child)  === "undefined") {
                        $el.find(p.selector).attr(p.attr, p.value +"-"+count);
                    // }else {
                    //     $el.find(p.selector).attr(p.attr, p.value +"-"+count+"-"+have_child);
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
                    $el.find(p.selector).attr(p.attr, "getcost(this.value," + count + ")");
            });
            }

            $el.hide().appendTo($(this).attr("data-container")).fadeIn();

        });

    })

    $(document).ready(function () {
        function calculatePaperDimensions() {
            let width = parseFloat($("#width").val()) || 0;
            let height = parseFloat($("#height").val()) || 0;
            let gusset = parseFloat($("#gusset").val()) || 0;
            let handleType = parseInt($("#handle_type").val()) || 0;

            if (handleType === 0) {
                $("#paper_width").val('');
                $("#paper_height").val('');
                return;
            }

            let paperWidth = ((width + gusset) * 2) + 2;
            let paperHeight = (height + (gusset / 2)) + 2;

            if (handleType === 2) {
                paperHeight += 3;
            }

            $("#paper_width").val(paperWidth.toFixed(2));
            $("#paper_height").val(paperHeight.toFixed(2));
        }

        $("#width, #height, #gusset, #handle_type").on("input change", calculatePaperDimensions);
    });

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\products\add.blade.php ENDPATH**/ ?>