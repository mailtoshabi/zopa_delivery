    <!-- Footer Section -->
    <footer class="bg-zopa py-4 mt-5">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-inline mb-2">
                        <li class="list-inline-item"><a href="<?php echo e(route('about_us')); ?>"><?php echo e(__('messages.menu.about')); ?></a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('how_to_use')); ?>"><?php echo e(__('messages.menu.how_to_use')); ?></a></li>
                        
                        <li class="list-inline-item"><a href="<?php echo e(route('payment_terms')); ?>"><?php echo e(__('messages.menu.payment_terms')); ?></a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('privacy_policy')); ?>"><?php echo e(__('messages.menu.privacy')); ?></a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('support')); ?>"><?php echo e(__('messages.menu.support')); ?></a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('faq')); ?>"><?php echo e(__('messages.menu.faq')); ?></a></li>
                        <li class="list-inline-item"><a href="<?php echo e(route('site_map')); ?>"><?php echo e(__('messages.menu.site_map')); ?></a></li>
                        <!-- Zopa Footer Install Link -->
                        <li><a href="#" id="footerInstallLink" style="display:none;">Install Zopa App</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p class="mb-0 d-md-inline">All rights reserved <?php echo config('app.name'); ?></p>
                    <span class="d-none d-md-inline"> | </span>
                    <p class="mb-0 d-md-inline">Powered by <a href="https://webmahal.com" target="_blank" class="powered" >Web Mahal</a></p>
                </div>
            </div>
        </div>
    </footer>

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
                            `<input type="date" id="meal-date" class="swal2-input form-control" value="${today}" min="${today}">` +
                            `<div style="margin-top: 10px; text-align: left;">
                                <label>
                                    <input type="checkbox" id="skip-addons"> Skip Addons
                                </label>
                            </div>`,
                        focusConfirm: false,
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        cancelButtonText: 'Cancel',
                        preConfirm: () => {
                            const quantity = parseInt(document.getElementById('meal-quantity').value);
                            const date = document.getElementById('meal-date').value;
                            const skipAddons = document.getElementById('skip-addons').checked;

                            if (!quantity || quantity <= 0) {
                                Swal.showValidationMessage('Please enter a valid quantity');
                            }

                            if (!date) {
                                Swal.showValidationMessage('Please select a valid date');
                            }

                            // Check if selected date is a Sunday
                            const selectedDay = new Date(date).getDay(); // 0 = Sunday
                            if (selectedDay === 0) {
                                Swal.showValidationMessage('Sunday is a holiday. Extra meals cannot be requested.');
                            }

                            return { quantity, date, skipAddons };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const { quantity, date, skipAddons } = result.value;

                            $.ajax({
                                url: '<?php echo e(route("customer.request.extra-meal")); ?>',
                                type: 'POST',
                                data: {
                                    _token: '<?php echo e(csrf_token()); ?>',
                                    quantity: quantity,
                                    date: date,
                                    skip_addons: skipAddons ? 1 : 0,
                                },
                                success: function (res) {
                                    Swal.fire('Success!', res.message, 'success').then(() => {
                                        window.location.href = '<?php echo e(route("customer.extra_meals")); ?>';
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
    let deferredPrompt;
    const footerInstallLink = document.getElementById('footerInstallLink');

    // Listen for beforeinstallprompt
    window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;

    // Show footer link
    footerInstallLink.style.display = 'inline';
    });

    // Handle footer install click
    footerInstallLink.addEventListener('click', (e) => {
e.preventDefault();
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
            console.log('User accepted install prompt from footer');
            footerInstallLink.style.display = 'none'; // Hide after install
            showInstallToast(); // optional — reuse the toast we made
        } else {
            console.log('User dismissed install prompt from footer');
        }
        deferredPrompt = null;
        });
    }
    });

    // Hide footer link when app is installed
    window.addEventListener('appinstalled', () => {
    console.log('Zopa App installed');
    footerInstallLink.style.display = 'none';
    });

    // Helper function to check if app is already installed
    function isAppInstalled() {
    return (window.matchMedia('(display-mode: standalone)').matches) || window.navigator.standalone === true;
    }

    // On page load, hide footer link if app already installed
    if (isAppInstalled()) {
    footerInstallLink.style.display = 'none';
    }
</script>

<style>
@media (max-width: 768px) {
    body {
        padding-bottom: 60px; /* Enough space for the fixed footer shortcuts */
    }
    .mobile-footer-shortcuts {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        border-top: 1px solid #ddd;
        display: flex;
        justify-content: space-around;
        padding: 8px 0;
        z-index: 9999;
        box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
    }
    .mobile-footer-shortcuts a {
        flex: 1;
        text-align: center;
        font-size: 14px;
        color: #333;
        text-decoration: none;
    }
    .mobile-footer-shortcuts a i {
        display: block;
        font-size: 18px;
        margin-bottom: 2px;
    }
}
</style>

<div class="mobile-footer-shortcuts d-md-none">
    <a href="<?php echo e(route('customer.daily_meals')); ?>">
        <i class="fa-solid fa-utensils" style="color: #696969;"></i>
        Meals
    </a>
    <a href="<?php echo e(route('front.meal.plan')); ?>">
        <i class="fa-solid fa-concierge-bell text-danger"></i>
        Plans
    </a>
    <a href="<?php echo e(route('front.show.addons')); ?>">
        <i class="fa-solid fa-plus-circle text-warning"></i>
        Addons
    </a>
    <a href="<?php echo e(route('my.wallet')); ?>">
        <i class="fa-solid fa-wallet" style="color: #8B4513;"></i>
        Wallet
    </a>
    <a href="https://wa.me/919809373738" target="_blank">
        <i class="fa-brands fa-whatsapp text-success"></i>
        Support
    </a>
</div>


<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\layouts\footer.blade.php ENDPATH**/ ?>