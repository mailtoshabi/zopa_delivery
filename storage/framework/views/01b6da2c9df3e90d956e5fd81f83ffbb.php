    <!-- Footer Section -->
    <footer class="bg-zopa py-4 mt-5">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-inline mb-3">
                        <li class="list-inline-item"><a href="<?php echo e(route('about_us')); ?>">About Us</a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('how_to_use')); ?>">How to Use</a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('payment_terms')); ?>">Payment Terms</a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('privacy_policy')); ?>">Privacy Policy</a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('support')); ?>">Support</a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('faq')); ?>">FAQ</a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('site_map')); ?>">Site Map</a></li>
                        <!-- Zopa Footer Install Link -->
                        <li><a href="#" id="footerInstallLink" style="display:none;">📲 Install Zopa App</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <p class="mb-0 d-md-inline">All rights reserved Zopa Food Drop</p>
                    <span class="d-none d-md-inline"> | </span>
                    <p class="mb-0 d-md-inline">Powered by <a href="https://webmahal.com" target="_blank" class="powered" >Web Mahal</a></p>
                </div>
            </div>
        </div>
    </footer>

    <?php
        use App\Http\Utilities\Utility;
        $lastOrderTime = Utility::CUTOFF_TIME;
    ?>

    <!-- Required JS -->
    <script src="<?php echo e(asset('front/js/jquery-3.6.0.min.js')); ?>" crossorigin="anonymous"></script>
    <script src="<?php echo e(asset('front/js/bootstrap.bundle.min.js')); ?>" crossorigin="anonymous"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    <script>
        function toggleMenu() {
            var menu = document.getElementById("mobileMenu");
            if (menu.classList.contains("active")) {
                menu.classList.remove("active");
            } else {
                menu.classList.add("active");
            }
        }

        function toggleSubmenu(event, submenuId) {
            event.preventDefault(); // Prevent default anchor behavior
            var submenu = document.getElementById(submenuId);
            if (submenu) {
                submenu.classList.toggle("active");
            }
        }
    </script>
    <script src="<?php echo e(asset('front/js/sweetalert2@11.js')); ?>"></script>


    <script>
        const lastOrderTime = <?php echo json_encode($lastOrderTime, 15, 512) ?>;

        $('.extra-meal-btn').on('click', function () {
            Swal.fire({
                title: 'Important Info',
                icon: 'info',
                html: `<div class="mb-2">
                    If you have balance on your meals wallet, a daily meal will be automatically generated at <strong>${lastOrderTime}</strong> every day.
                    <br><br>
                    You only need to request an <strong>Extra Meal</strong> if you want more than your daily allocation.
                </div>`,
                showCancelButton: true,
                confirmButtonText: 'Request Extra Meal',
                cancelButtonText: 'Cancel',
            }).then((infoResult) => {
                if (infoResult.isConfirmed) {
                    const today = new Date().toISOString().split('T')[0];

                    Swal.fire({
                        title: 'Request Extra Meal',
                        html:
                            `<input type="number" id="meal-quantity" class="swal2-input form-control" placeholder="Enter quantity" min="1" value="1">` +
                            `<input type="date" id="meal-date" class="swal2-input form-control" value="${today}" min="${today}">`,
                        focusConfirm: false,
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        cancelButtonText: 'Cancel',
                        preConfirm: () => {
                            const quantity = parseInt(document.getElementById('meal-quantity').value);
                            const date = document.getElementById('meal-date').value;

                            if (!quantity || quantity <= 0) {
                                Swal.showValidationMessage('Please enter a valid quantity');
                            }

                            if (!date) {
                                Swal.showValidationMessage('Please select a valid date');
                            }

                            return { quantity, date };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const { quantity, date } = result.value;

                            $.ajax({
                                url: '<?php echo e(route("customer.request.extra-meal")); ?>',
                                type: 'POST',
                                data: {
                                    _token: '<?php echo e(csrf_token()); ?>',
                                    quantity: quantity,
                                    date: date
                                },
                                success: function (res) {
                                    Swal.fire('Success!', res.message, 'success').then(() => {
                                        window.location.href = '<?php echo e(route("customer.daily_meals")); ?>';
                                    });
                                },
                                error: function (xhr) {
                                    const msg = xhr.responseJSON?.message || 'Something went wrong!';
                                    Swal.fire('Error', msg, 'error');
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
    <script>
    $(document).ready(function() {
        $(document).on('click', '.makeButtonDisable', function(e) {
            const $btn = $(this);

            // If already disabled, block
            if ($btn.hasClass('disabled')) {
                e.preventDefault();
                return;
            }

            // Disable button & visually dim
            $btn.addClass('disabled').css('pointer-events', 'none').css('opacity', 0.6);

            // Optionally show spinner (if you want)
            $btn.html('<i class="fa fa-spinner fa-spin"></i> Processing...');
        });
    });
</script>

<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('<?php echo e(asset("front/js/sw.js")); ?>').then(function(reg) {
      console.log('Zopa SW registered:', reg);
    }).catch(function(error) {
      console.log('Zopa SW registration failed:', error);
    });
  }
</script>



<!-- Install App Modal -->

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\layouts\footer_old.blade.php ENDPATH**/ ?>