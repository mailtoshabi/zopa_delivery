<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.Add_Kitchen'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/select2/select2.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(URL::asset('assets/libs/dropzone/dropzone.min.css')); ?>" rel="stylesheet">
<style>
.file-input-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
}
.file-input-wrapper input[type="file"] {
    width: 100%;
    padding-right: 2.5rem; /* Make space for button */
}
.file-input-wrapper .btn-close {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    border: none;
}
</style>
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
                            <div class="mb-3">
                                <label for="display_name"><?php echo app('translator')->get('translation.Display_Name'); ?></label>
                                <input id="display_name" name="display_name" type="text" class="form-control"  placeholder="<?php echo app('translator')->get('translation.Display_Name'); ?>" value="<?php echo e(isset($kitchen)?$kitchen->display_name:old('display_name')); ?>">
                                <?php $__errorArgs = ['display_name'];
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

                        <div class="col-sm-12 required">
                            <label for="postal_code">Location</label>

                            <!-- Input group for input + tooltip button -->
                            <div class="input-group mb-2">
                                <input type="text" id="autocomplete" placeholder="Enter location" class="form-control" value="<?php echo e(isset($kitchen)?$kitchen->location_name:old('location_name')); ?>">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="getCurrentLocation()"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="left"
                                    title="Use current location">
                                    📍
                                </button>
                            </div>

                            <!-- Hidden fields -->
                            <input type="hidden" name="latitude" id="latitude" value="<?php echo e(isset($kitchen)?$kitchen->latitude:old('latitude')); ?>">
                            <input type="hidden" name="longitude" id="longitude" value="<?php echo e(isset($kitchen)?$kitchen->longitude:old('longitude')); ?>">
                            <input type="hidden" name="location_name" id="location_name" value="<?php echo e(isset($kitchen)?$kitchen->location_name:old('location_name')); ?>">

                            <!-- Map -->
                            <div id="map" style="height: 300px; width: 100%; margin-top: 10px;"></div>
                        </div>




                        
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Images & Documents</h4>
                    
                </div>
                <div class="card-body">

                        <div class="row">
                            <div class="col-sm-6 mb-3" id="imageWrap">
                                <label class="form-label">Main Kitchen Image</label>
                                <span id="imageContainer" <?php if(isset($kitchen)&&empty($kitchen->image_filename)): ?> style="display: none" <?php endif; ?>>
                                    <?php if(isset($kitchen)&&!empty($kitchen->image_filename)): ?>
                                        <br><img src="<?php echo e(Storage::url(App\Models\Kitchen::DIR_PUBLIC . '/' . $kitchen->image_filename)); ?>" alt="" class="avatar-xxl rounded-circle me-2">
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    <?php endif; ?>
                                </span>

                                <span id="fileContainer" <?php if(isset($kitchen)&&!empty($kitchen->image_filename)): ?> style="display: none" <?php endif; ?>>
                                    <div class="d-flex align-items-center gap-2">
                                    <input id="image" name="image" type="file" class="form-control"  placeholder="File">
                                    <?php if(isset($kitchen)&&!empty($kitchen->image_filename)): ?>
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    <?php endif; ?>
                                    </div>
                                </span>
                                <input name="isImageDelete" type="hidden" value="0">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Additional Images</label>
                                <input type="file" name="additional_images[]" class="form-control" multiple>
                                <?php if(!empty($meal->additional_images)): ?>
                                    <div class="mt-2 d-flex flex-wrap gap-2">
                                        <?php $__currentLoopData = json_decode($meal->additional_images); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <img src="<?php echo e(asset('storage/meals/' . $image)); ?>" alt="additional" class="img-thumbnail" width="80">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="license_number">License Number</label>
                                <input id="license_number" name="license_number" type="text" class="form-control"  placeholder="License Number" value="<?php echo e(isset($kitchen)?$kitchen->license_number:old('license_number')); ?>">
                                <?php $__errorArgs = ['license_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-sm-6 mb-3" id="licenseWrap">
                                <label class="form-label">License Document</label>
                                <span id="license_fileImageDiv" <?php if(isset($kitchen)&&empty($kitchen->license_file)): ?> style="display: none" <?php endif; ?>>
                                    <div class="file-input-wrapper">
                                    <?php if(isset($kitchen)&&!empty($kitchen->license_file)): ?>
                                    <br><a target="_blank" href="<?php echo e(Storage::url(App\Models\Kitchen::DIR_PUBLIC_LICESNSE . '/' . $kitchen->license_file)); ?>">
                                        View File <i class="fas fa-file"></i>
                                    </a>
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    <?php endif; ?>
                                    </div>
                                </span>
                                <span id="license_fileInputDiv" <?php if(isset($kitchen)&&!empty($kitchen->license_file)): ?> style="display: none" <?php endif; ?>>
                                    <div class="file-input-wrapper">
                                    <input id="license_file" name="license_file" type="file" class="form-control"  placeholder="File">
                                    <?php if(isset($kitchen)&&!empty($kitchen->license_file)): ?>
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    <?php endif; ?>
                                    </div>
                                </span>
                                <input name="isLicenseDelete" type="hidden" value="0">
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label for="fssai_number">FSSAI Number</label>
                                <input id="fssai_number" name="fssai_number" type="text" class="form-control"  placeholder="FSSAI Number" value="<?php echo e(isset($kitchen)?$kitchen->fssai_number:old('fssai_number')); ?>">
                                <?php $__errorArgs = ['fssai_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-danger"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <label class="form-label">FSSAI Document</label>
                                <span id="fssai_certificateImageDiv" <?php if(isset($kitchen)&&empty($kitchen->fssai_certificate)): ?> style="display: none" <?php endif; ?>>
                                    <div class="file-input-wrapper">
                                    <?php if(isset($kitchen)&&!empty($kitchen->fssai_certificate)): ?>
                                    <br><a target="_blank" href="<?php echo e(Storage::url(App\Models\Kitchen::DIR_PUBLIC_FSSAI . '/' . $kitchen->fssai_certificate)); ?>">
                                        View File <i class="fas fa-file"></i>
                                    </a>
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    <?php endif; ?>
                                    </div>
                                </span>
                                <span id="fssai_certificateInputDiv" <?php if(isset($kitchen)&&!empty($kitchen->fssai_certificate)): ?> style="display: none" <?php endif; ?>>
                                    <div class="file-input-wrapper">
                                    <input id="fssai_certificate" name="fssai_certificate" type="file" class="form-control"  placeholder="File">
                                    <?php if(isset($kitchen)&&!empty($kitchen->fssai_certificate)): ?>
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    <?php endif; ?>
                                    </div>
                                </span>
                                <input name="isfssai_certificateDelete" type="hidden" value="0">
                            </div>

                            <div class="col-sm-12 mb-3">
                                <label class="form-label">Other Documents</label>
                                <span id="other_documentsImageDiv" <?php if(isset($kitchen)&&empty($kitchen->other_documents)): ?> style="display: none" <?php endif; ?>>
                                    <div class="file-input-wrapper">
                                    <?php if(isset($kitchen)&&!empty(json_decode($kitchen->other_documents, true))): ?>
                                    <br>
                                    <?php $__currentLoopData = json_decode($kitchen->other_documents); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $other_document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a target="_blank" href="<?php echo e(Storage::url(App\Models\Kitchen::DIR_PUBLIC_OTHDOC . '/' . $other_document)); ?>">
                                            View File <i class="fas fa-file"></i>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    <?php endif; ?>
                                    </div>
                                </span>
                                <span id="other_documentsInputDiv" <?php if(isset($kitchen)&&!empty($kitchen->other_documents)): ?> style="display: none" <?php endif; ?>>
                                    <div class="file-input-wrapper">
                                    <input id="other_documents" name="other_documents[  ]" type="file" class="form-control"  placeholder="File" multiple>
                                    <?php if(isset($kitchen)&&!empty($kitchen->other_documents)): ?>
                                        <button type="button" class="btn-close" aria-label="Close">x</button>
                                    <?php endif; ?>
                                    </div>
                                </span>
                                <input name="isother_documentsDelete" type="hidden" value="0">
                            </div>


                        </div>

                </div>

            </div> <!-- end card-->


            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Login Information</h4>
                    <p class="card-title-desc">Fill all information below</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="text" class="form-control" placeholder="Email" value="<?php echo e(isset($kitchen)?$kitchen->email:old('email')); ?>">
                                <?php $__errorArgs = ['email'];
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
                                <label for="horizontal-password-input">Password</label>
                                <input type="password" name="password" class="form-control" id="horizontal-password-input" placeholder="Enter Your Password">
                                <?php $__errorArgs = ['password'];
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
$(function () {
    $('#imageWrap .btn-close').click(function () {
        $('#imageContainer, #fileContainer').toggle(); // Toggle both
        const isVisible = $('#imageContainer').is(':visible');
        $('[name="isImageDelete"]').val(isVisible ? 0 : 1);
    });

    $('#licenseWrap .btn-close').click(function () {
        $('#license_fileImageDiv, #license_fileInputDiv').toggle(); // Toggle both
        const isVisible = $('#license_fileImageDiv').is(':visible');
        $('[name="isLicenseDelete"]').val(isVisible ? 0 : 1);
    });
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

<script
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBlsEVjPfChYExnraJoKmt7aG7ItrPZ9TA&libraries=places&callback=initAutocomplete"
  async
  defer>
</script>
<script>
    let map;
    let marker;

    function initAutocomplete() {
        let input = document.getElementById('autocomplete');
        let autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.setFields(['geometry', 'formatted_address']);

        const defaultLat = 10.8505;  // Fallback default
        const defaultLng = 76.2711;
        const lat = parseFloat(document.getElementById('latitude').value) || defaultLat;
        const lng = parseFloat(document.getElementById('longitude').value) || defaultLng;

        // Initialize map
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: lat, lng: lng },
            zoom: (lat !== defaultLat && lng !== defaultLng) ? 15 : 10
        });

        // Initialize marker
        marker = new google.maps.Marker({
            map: map,
            position: { lat: lat, lng: lng },
            draggable: true
        });

        // Update fields when marker is dragged
        marker.addListener('dragend', function () {
            const position = marker.getPosition();
            document.getElementById('latitude').value = position.lat();
            document.getElementById('longitude').value = position.lng();

            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: position }, function (results, status) {
                if (status === 'OK' && results[0]) {
                    document.getElementById('location_name').value = results[0].formatted_address;
                    document.getElementById('autocomplete').value = results[0].formatted_address;
                }
            });
        });

        // Handle autocomplete selection
        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();
            if (!place.geometry) return;

            const location = place.geometry.location;
            map.setCenter(location);
            map.setZoom(15);
            marker.setPosition(location);

            document.getElementById('latitude').value = location.lat();
            document.getElementById('longitude').value = location.lng();
            document.getElementById('location_name').value = place.formatted_address;
        });
    }

    // google.maps.event.addDomListener(window, 'load', initAutocomplete);



    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                const latlng = new google.maps.LatLng(lat, lng);
                const geocoder = new google.maps.Geocoder();

                geocoder.geocode({ location: latlng }, (results, status) => {
                    if (status === "OK" && results[0]) {
                        document.getElementById('autocomplete').value = results[0].formatted_address;
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lng;
                        document.getElementById('location_name').value = results[0].formatted_address;

                        map.setCenter(latlng);
                        map.setZoom(15);
                        marker.setPosition(latlng);
                    }
                });
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\kitchens\create.blade.php ENDPATH**/ ?>