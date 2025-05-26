<?php $__env->startSection('title', 'My Zopa Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-4">
    <h2 class="mb-4">My Zopa Profile</h2>

    
    <div id="profileView">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Profile Details</span>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary" id="editProfileBtn">
                        <i class="fas fa-edit me-1"></i> Edit Profile
                    </button>
                    <a href="<?php echo e(route('customer.profile.password.change')); ?>" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-key me-1"></i> Change Password
                    </a>
                </div>

            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong>Full Name:</strong><br> <?php echo e($customer->name); ?>

                    </div>
                    <div class="col-md-6">
                        <strong>Shop/Office Name:</strong><br> <?php echo e($customer->office_name); ?>

                    </div>
                    <div class="col-md-6">
                        <strong>Job Designation:</strong><br> <?php echo e($customer->designation ?? '-'); ?>

                    </div>
                    <div class="col-md-6">
                        <strong>Whatsapp Number:</strong><br> <?php echo e($customer->whatsapp ?? '-'); ?>

                    </div>
                    <div class="col-md-6">
                        <strong>Shop/Office Location:</strong><br> <?php echo e($customer->city); ?>

                    </div>
                    <div class="col-md-6">
                        <strong>Landmark:</strong><br> <?php echo e($customer->landmark ?? '-'); ?>

                    </div>
                    <div class="col-md-6">
                        <strong>Postal Code:</strong><br> <?php echo e($customer->postal_code); ?>

                    </div>
                    <div class="col-md-6">
                        <strong>State:</strong><br> <?php echo e(optional($customer->state)->name); ?>

                    </div>
                    <div class="col-md-6">
                        <strong>District:</strong><br> <?php echo e(optional($customer->district)->name); ?>

                    </div>
                    <div class="col-md-6">
                        <strong>Profile Image:</strong><br>
                        <?php if(!empty($customer->image_filename)): ?>
                            <img src="<?php echo e(Storage::url(App\Models\Customer::DIR_PUBLIC.'/'.$customer->image_filename)); ?>" alt="Profile Image" class="rounded img-thumbnail mt-2" width="120">
                        <?php else: ?>
                            <span class="text-muted">No Image</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div id="profileForm" style="display: none;">
        <form method="POST" action="<?php echo e(route('customer.profile.update')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Edit Profile</span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="cancelEditBtn">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                </div>
                <div class="card-body row g-3">

                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input id="name" name="name" type="text" class="form-control" placeholder="Full Name" value="<?php echo e(old('name', $customer->name)); ?>">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label for="office_name" class="form-label">Shop/Office Name <span class="text-danger">*</span></label>
                        <input id="office_name" name="office_name" type="text" class="form-control" placeholder="Shop/Office Name" value="<?php echo e(old('office_name', $customer->office_name)); ?>">
                        <?php $__errorArgs = ['office_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label for="designation" class="form-label">Job Designation</label>
                        <input id="designation" name="designation" type="text" class="form-control" placeholder="Job Designation" value="<?php echo e(old('designation', $customer->designation)); ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="whatsapp" class="form-label">Whatsapp Number <small class="text-muted">(optional)</small></label>
                        <input id="whatsapp" name="whatsapp" type="text" class="form-control" placeholder="Whatsapp Number" value="<?php echo e(old('whatsapp', $customer->whatsapp)); ?>">
                        <?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label for="city" class="form-label">Shop/Office Location <span class="text-danger">*</span></label>
                        <input id="city" name="city" type="text" class="form-control" placeholder="City" value="<?php echo e(old('city', $customer->city)); ?>">
                        <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6">
                        <label for="landmark" class="form-label">Landmark</label>
                        <input id="landmark" name="landmark" type="text" class="form-control" placeholder="Landmark" value="<?php echo e(old('landmark', $customer->landmark)); ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="postal_code" class="form-label">Postal Code <span class="text-danger">*</span></label>
                        <input id="postal_code" name="postal_code" type="text" class="form-control" placeholder="Postal Code" value="<?php echo e(old('postal_code', $customer->postal_code)); ?>">
                        <?php $__errorArgs = ['postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    

                    <div class="col-md-6">
                        <label class="form-label">Profile Image</label>
                        <div class="text-center">
                            <span id="imageContainer" <?php if(empty($customer->image_filename)): ?> style="display:none;" <?php endif; ?>>
                                <?php if(!empty($customer->image_filename)): ?>
                                    <img src="<?php echo e(Storage::url(App\Models\Customer::DIR_PUBLIC . '/' . $customer->image_filename)); ?>" alt="Profile Image" class="rounded-circle img-thumbnail mb-2" width="120">
                                    <br>
                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="removeImageBtn">Remove Image</button>
                                <?php endif; ?>
                            </span>

                            <span id="fileContainer" <?php if(!empty($customer->image_filename)): ?> style="display:none;" <?php endif; ?>>
                                <input type="file" id="imageInput" name="image" class="form-control">
                            </span>

                            <input type="hidden" name="isImageDelete" value="0">
                        </div>
                    </div>

                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="submit" class="btn btn-zopa">
                    <i class="fas fa-save me-1"></i> Update Profile
                </button>
                <button type="button" class="btn btn-outline-secondary" id="cancelEditBtnBottom">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // function getDistrict(stateId, selectedDistrictId = 0) {
    //     if (!stateId) return;
    //     $.ajax({
    //         type: 'POST',
    //         url: "<?php echo e(route('get.districts')); ?>",
    //         data: { s_id: stateId, d_id: selectedDistrictId, _token: '<?php echo e(csrf_token()); ?>' },
    //         success: function(data) {
    //             $('#district-list').html(data);
    //         }
    //     });
    // }


    $(document).ready(function() {
        const $profileView = $('#profileView');
        const $profileForm = $('#profileForm');

        $('#editProfileBtn').click(function() {
            $profileView.fadeOut(200, function() {
                $profileForm.fadeIn(200, function() {
                    $('html, body').animate({
                        scrollTop: $profileForm.offset().top - 100
                    }, 400);
                    $('#name').focus();
                });
            });
        });

        $('#cancelEditBtn, #cancelEditBtnBottom').click(function() {
            $profileForm.fadeOut(200, function() {
                $profileView.fadeIn(200);

                $('input[name="isImageDelete"]').val(0);
                <?php if(!empty($customer->image_filename)): ?>
                    $('#fileContainer').hide();
                    $('#imageContainer').show();
                <?php else: ?>
                    $('#fileContainer').show();
                    $('#imageContainer').hide();
                <?php endif; ?>
                $('#previewImage').remove();
                $('#imageInput').val('');
            });
        });

        $('#removeImageBtn').click(function() {
            $('#imageContainer').slideUp(200);
            $('#fileContainer').slideDown(200);
            $('input[name="isImageDelete"]').val(1);
        });

        $('form').on('submit', function() {
            $(this).find('button[type=submit]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');
            $(this).find('input[type=text]').each(function() {
                $(this).val($.trim($(this).val()));
            });
        });

        $('#imageInput').on('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').remove(); // Remove any old preview
                    $('<img>', {
                        id: 'previewImage',
                        src: e.target.result,
                        class: 'img-thumbnail mt-2',
                        width: 150,
                        alt: 'Preview'
                    }).insertAfter('#imageInput');
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>

<?php if(session('success')): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '<?php echo e(session('success')); ?>',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\profile.blade.php ENDPATH**/ ?>