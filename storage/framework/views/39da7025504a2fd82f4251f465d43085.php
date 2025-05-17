<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Add_Kitchen'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/select2/select2.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/dropzone/dropzone.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('admin.dir_components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> <?php echo app('translator')->get('translation.Account_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('li_2'); ?> <?php echo app('translator')->get('translation.Kitchen_Manage'); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> <?php if(isset($kitchen)): ?> <?php echo app('translator')->get('translation.Edit_Kitchen'); ?> <?php else: ?> <?php echo app('translator')->get('translation.Add_Kitchen'); ?> <?php endif; ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<div class="row">
    <form method="POST" action="<?php echo e(isset($kitchen)? route('admin.kitchens.update',encrypt($kitchen->id)) : route('admin.kitchens.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if(isset($kitchen)): ?>
            
            <?php echo method_field('PUT'); ?>
        <?php endif; ?>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?php echo app('translator')->get('translation.Kitchen'); ?> Details</h4>
                    <p class="card-title-desc  required"><?php echo e(isset($kitchen)? 'Edit' : "Enter"); ?> the Details of your <?php echo app('translator')->get('translation.Kitchen'); ?>, Noted with <label></label> are mandatory.</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3 required">
                                <label for="name"><?php echo app('translator')->get('translation.Name'); ?></label>
                                <input id="name" name="name" type="text" class="form-control"  placeholder="<?php echo app('translator')->get('translation.Name'); ?>" value="<?php echo e(isset($kitchen)?$kitchen->name:old('name')); ?>">
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div class="mb-3 required">
                                <label for="phone">Phone</label>
                                <input id="phone" name="phone" type="text" class="form-control"  placeholder="Phone" value="<?php echo e(isset($kitchen)?$kitchen->phone:old('phone')); ?>">
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mb-3">
                                <label for="whatsapp">WhatsApp</label>
                                <input id="whatsapp" name="whatsapp" type="text" class="form-control" placeholder="WhatsApp" value="<?php echo e(isset($kitchen)?$kitchen->whatsapp:old('whatsapp')); ?>">
                                <?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mb-3">
                                <label for="address1">Address Line 1</label>
                                <input id="address1" name="address1" type="text" class="form-control"  placeholder="Building Number" value="<?php echo e(isset($kitchen)?$kitchen->address1:old('address1')); ?>">
                                
                            </div>
                            <div class="mb-3">
                                <label for="address2">Address Line 2</label>
                                <input id="address2" name="address2" type="text" class="form-control"  placeholder="Street" value="<?php echo e(isset($kitchen)?$kitchen->address2:old('address2')); ?>">
                                
                            </div>


                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="address3">Address Line 3</label>
                                <input id="address3" name="address3" type="text" class="form-control"  placeholder="Street" value="<?php echo e(isset($kitchen)?$kitchen->address3:old('address3')); ?>">
                                
                            </div>
                            <div class="mb-3 required">
                                <label for="city">City</label>
                                <input id="city" name="city" type="text" class="form-control"  placeholder="City" value="<?php echo e(isset($kitchen)?$kitchen->city:old('city')); ?>">
                                <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mb-3">
                                <label for="postal_code">Postal Code</label>
                                <input id="postal_code" name="postal_code" type="text" class="form-control"  placeholder="Postal Code" value="<?php echo e(isset($kitchen)?$kitchen->postal_code:old('postal_code')); ?>">
                                
                            </div>

                            <div class="mb-3 required">
                                <label class="control-label">State</label>
                                <select id="state_id" name="state_id" class="form-control select2" onChange="getdistrict(this.value,0);">
                                    <option value="">Select State</option>
                                    <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($state->id); ?>" <?php echo e($state->id==Utility::STATE_ID_KERALA ? 'selected':''); ?>><?php echo e($state->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['state_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mb-3 required">
                                <label class="control-label">District</label>
                                <select name="district_id" id="district-list" class="form-control select2">
                                    <option value="">Select District</option>
                                </select>
                                <?php $__errorArgs = ['district_id'];
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
                    <h4 class="card-title mb-0">Image</h4>
                    <p class="card-title-desc">Upload Image of your <?php echo app('translator')->get('translation.Kitchen'); ?>, if any</p>
                </div>
                <div class="card-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <span id="imageContainer" <?php if(isset($kitchen)&&empty($kitchen->image_filename)): ?> style="display: none" <?php endif; ?>>
                                        <?php if(isset($kitchen)&&!empty($kitchen->image_filename)): ?>
                                            <img src="<?php echo e(Storage::url(App\Models\Kitchen::DIR_PUBLIC . '/' . $kitchen->image_filename)); ?>" alt="" class="avatar-xxl rounded-circle me-2">
                                            <button type="button" class="btn-close" aria-label="Close"></button>
                                        <?php endif; ?>
                                    </span>

                                    <span id="fileContainer" <?php if(isset($kitchen)&&!empty($kitchen->image_filename)): ?> style="display: none" <?php endif; ?>>
                                        <input id="image" name="image" type="file" class="form-control"  placeholder="File">
                                        <?php if(isset($kitchen)&&!empty($kitchen->image_filename)): ?>
                                            <button type="button" class="btn-close" aria-label="Close"></button>
                                        <?php endif; ?>
                                    </span>
                                    <input name="isImageDelete" type="hidden" value="0">
                                </div>
                            </div>
                        </div>

                </div>

            </div> <!-- end card-->

            

            

            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save Changes</button>
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
<script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script>
<script>
    $(document).ready(function() {
        <?php if(isset($kitchen)): ?>
            getdistrict(<?php echo e(Utility::STATE_ID_KERALA); ?>,<?php echo e($kitchen->district_id); ?>);
        <?php else: ?>
            getdistrict(<?php echo e(Utility::STATE_ID_KERALA); ?>,0);
        <?php endif; ?>
    });
    function getdistrict(val,d_id) {
        var formData = {'s_id' : val, 'd_id':d_id};
        $.ajax({
            type: "POST",
            url: "<?php echo e(route('admin.list.districts')); ?>",
            data:formData,
            success: function(data){
                $("#district-list").html(data);
                // console.log(data);
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        $('#imageContainer').find('button').click(function() {
            $('#imageContainer').hide();
            $('#fileContainer').show();
            $('input[name="isImageDelete"]').val(1);
        })

        $('#fileContainer').find('button').click(function() {
            $('#fileContainer').hide();
            $('#imageContainer').show();
            $('input[name="isImageDelete"]').val(0);
        })
    });
</script>

<script>
    $(document).ready(function() {
        // $('.select2_rent_terms').select2();
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
            $.each(plugins, function(i, p) {
                if(p.plugin=='select2') {
                    //$el.find(p.selector).select2();
                }

            });

            $el.hide().appendTo($(this).attr("data-container")).fadeIn();

        });

    })
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\kitchens\create.blade.php ENDPATH**/ ?>