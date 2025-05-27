<?php $__env->startSection('title', 'My Zopa Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-4">
    <h2 class="mb-4">My Zopa Profile</h2>

    

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>


    
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
        <form id="updateProfileForm" method="POST" action="<?php echo e(route('customer.profile.update')); ?>" enctype="multipart/form-data">
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
                                <input type="file" id="imageInput" name="image" class="form-control" accept="image/*">
                                <input type="hidden" name="cropped_image" id="croppedImageData">
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

<!-- Crop Modal -->
<div id="cropModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.6); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; padding:20px; border-radius:8px; max-width:90%; max-height:90%; overflow:auto; position:relative; width:100%; max-width:500px;">

        <!-- Button Row (Flexbox) -->
        <div style="display:flex; justify-content:space-between; gap:10px; position:absolute; top:10px; left:10px; right:10px; z-index:10;">
            <button id="resetButton" class="btn btn-warning w-100">Reset</button>
            <button id="closeCropModal" class="btn btn-danger w-100">Close</button>
            <button id="cropButton" class="btn btn-success w-100">Crop</button>
        </div>

        <!-- Image Preview -->
        <img id="imagePreview" style="max-width:100%; display:block; margin:0 auto; margin-top:70px;">
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
    <style>
        .flash-message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 12px 20px;
            border-radius: 8px;
            background-color: #4ade80; /* green-400 */
            color: white;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            animation: fadeOut 4s ease forwards;
        }

        .flash-message.alert-danger {
            background-color: #f87171; /* red-400 */
        }

        @keyframes fadeOut {
            0% { opacity: 1; }
            80% { opacity: 1; }
            100% { opacity: 0; transform: translateY(-10px); }
        }
    </style>
    <!-- Cropper CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <!-- Cropper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;

        // Open Crop Modal on image selection
        document.getElementById('imageInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const image = document.getElementById('imagePreview');
                    image.src = event.target.result;
                    document.getElementById('cropModal').style.display = 'flex';

                    if (cropper) cropper.destroy();
                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 1,
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        // Crop and save image
        document.getElementById('cropButton').addEventListener('click', function () {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300,
                });
                canvas.toBlob(function (blob) {
                    const reader = new FileReader();
                    reader.onloadend = function (e) {
                        document.getElementById('croppedImageData').value = reader.result;
                        document.getElementById('cropModal').style.display = 'none';

                        $('#previewImage').remove(); // Remove old preview
                        $('<img>', {
                            id: 'previewImage',
                            src: e.target.result,
                            class: 'img-thumbnail mt-2',
                            width: 150,
                            alt: 'Preview'
                        }).insertAfter('#imageInput');
                    };
                    reader.readAsDataURL(blob);
                });
            }
        });

        // Reset cropper
        document.getElementById('resetButton').addEventListener('click', function () {
            if (cropper) cropper.reset();
        });

        // Close without saving
        document.getElementById('closeCropModal').addEventListener('click', function () {
            document.getElementById('cropModal').style.display = 'none';
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            document.getElementById('imagePreview').src = '';
            document.getElementById('imageInput').value = '';
            $('#previewImage').remove();
        });

        // Optional: Remove uploaded image logic
        document.getElementById('removeImageBtn')?.addEventListener('click', function () {
            document.querySelector('input[name="isImageDelete"]').value = '1';
            document.getElementById('imageContainer').style.display = 'none';
            document.getElementById('fileContainer').style.display = 'block';
        });

        // Optional: Edit profile toggle logic
        document.getElementById('editProfileBtn')?.addEventListener('click', function () {
            document.getElementById('profileView').style.display = 'none';
            document.getElementById('profileForm').style.display = 'block';
        });

        document.getElementById('cancelEditBtn')?.addEventListener('click', cancelEdit);
        document.getElementById('cancelEditBtnBottom')?.addEventListener('click', cancelEdit);

        function cancelEdit() {
            document.getElementById('profileForm').style.display = 'none';
            document.getElementById('profileView').style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
    <script>
        $(document).ready(function () {
            setTimeout(function () {
                $('.flash-message').fadeOut('slow');
            }, 4000); // 4 seconds
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
        });
    </script>
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

            // $('#imageInput').on('change', function(e) {
            //     const file = e.target.files[0];
            //     if (file && file.type.startsWith('image/')) {
            //         const reader = new FileReader();
            //         reader.onload = function(e) {
            //             $('#previewImage').remove(); // Remove any old preview
            //             $('<img>', {
            //                 id: 'previewImage',
            //                 src: e.target.result,
            //                 class: 'img-thumbnail mt-2',
            //                 width: 150,
            //                 alt: 'Preview'
            //             }).insertAfter('#imageInput');
            //         };
            //         reader.readAsDataURL(file);
            //     }
            // });


            AJAX profile update
            $('#updateProfileForm').on('submit', function (e) {
                e.preventDefault();

                var form = $(this)[0];
                var formData = new FormData(form);

                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#updateProfileForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');
                        // Show loading overlay
                        document.getElementById("loading-overlay").style.display = "flex";
                    },
                    success: function (response) {
                        // Show success message
                        $('body').prepend('<div class="alert alert-success flash-message">Profile updated successfully.</div>');
                        setTimeout(() => $('.flash-message').fadeOut('slow'), 4000);

                        // Switch back to view mode
                        $('#profileView').show();
                        $('#profileForm').hide();
                        location.reload(); // or refresh only part of the profile if you want
                    },
                    error: function (xhr) {
                        $('.flash-message').remove();

                        document.getElementById("loading-overlay").style.display = "none";

                        let errors = xhr.responseJSON?.errors;
                        if (errors) {
                            let errorHtml = '<div class="alert alert-danger"><ul>';
                            $.each(errors, function (key, messages) {
                                errorHtml += '<li>' + messages[0] + '</li>';
                            });
                            errorHtml += '</ul></div>';
                            $('#profileForm').prepend(errorHtml);
                        } else {
                            alert('Something went wrong. Please try again.');
                        }
                    },
                    complete: function () {
                        $('#updateProfileForm button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save me-1"></i> Update Profile');
                        document.getElementById("loading-overlay").style.display = "none";
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search);
            if (params.get('edit') === 'true') {
                document.getElementById('profileForm').style.display = 'block';
                document.getElementById('profileView').style.display = 'none';
                // Optional: scroll into view
                document.getElementById('profileForm').scrollIntoView({ behavior: 'smooth' });
            }
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views/pages/profile.blade.php ENDPATH**/ ?>