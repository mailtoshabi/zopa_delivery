    <!-- Footer Section -->
    <footer class="bg-zopa py-4 mt-5">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-inline mb-3">
                        <li class="list-inline-item"><a href="{{ route('about_us') }}">About Us</a></li>
                        <li class="list-inline-item"><a href="{{ route('how_to_use') }}">How to Use</a></li>
                        <li class="list-inline-item"><a href="{{ route('payment_terms') }}">Payment Terms</a></li>
                        <li class="list-inline-item"><a href="{{ route('privacy_policy') }}">Privacy Policy</a></li>
                        <li class="list-inline-item"><a href="{{ route('support') }}">Support</a></li>
                        <li class="list-inline-item"><a href="{{ route('faq') }}">FAQ</a></li>
                        <li class="list-inline-item"><a href="{{ route('site_map') }}">Site Map</a></li>
                        <!-- Zopa Footer Install Link -->
                        <li><a href="#" id="footerInstallLink" style="display:none;">📲 Install Zopa App</a></li>
                    </ul>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <p class="mb-0 d-md-inline">All rights reserved @appName</p>
                    <span class="d-none d-md-inline"> | </span>
                    <p class="mb-0 d-md-inline">Powered by <a href="https://webmahal.com" target="_blank" class="powered" >Web Mahal</a></p>
                </div>
            </div>
        </div>
    </footer>

    @php
        use App\Http\Utilities\Utility;

        if(auth('customer')->check()) {
            $lastOrderTime = App\Helpers\FileHelper::convertTo12Hour(auth('customer')->user()->cutoff_time); // From accessor
        } else {
            $lastOrderTime = App\Helpers\FileHelper::convertTo12Hour(Utility::CUTOFF_TIME);
        }
    @endphp

    <!-- Required JS -->
    <script src="{{ asset('front/js/jquery-3.6.0.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('front/js/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>

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
    <script src="{{ asset('front/js/sweetalert2@11.js') }}"></script>


    <script>
        const lastOrderTime = @json($lastOrderTime);

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
                                url: '{{ route("customer.request.extra-meal") }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    quantity: quantity,
                                    date: date
                                },
                                success: function (res) {
                                    Swal.fire('Success!', res.message, 'success').then(() => {
                                        window.location.href = '{{ route("customer.daily_meals") }}';
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
    navigator.serviceWorker.register('{{ asset("front/js/sw.js") }}').then(function(reg) {
      console.log('Zopa SW registered:', reg);
    }).catch(function(error) {
      console.log('Zopa SW registration failed:', error);
    });
  }
</script>
{{-- <script>
    let deferredPrompt;

    function isAppInstalled() {
    if (window.matchMedia('(display-mode: standalone)').matches) {
        return true;
    }
    if (window.navigator.standalone === true) {
        return true;
    }
    return false;
    }

    // Only show install prompt on these paths
    const INSTALL_ALLOWED_PATHS = ['/','/about-us'];

    function isInstallPromptAllowedOnThisPage() {
    const currentPath = window.location.pathname;
    return INSTALL_ALLOWED_PATHS.includes(currentPath);
    }

    window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;

    if (isAppInstalled() || isInstallPromptDismissed() || !isInstallPromptAllowedOnThisPage()) {
        return; // Don’t show if already installed, dismissed, or wrong page
    }

    const installModal = new bootstrap.Modal(document.getElementById('installAppModal'));

    setTimeout(() => installModal.show(), 5000);

    const confirmInstallBtn = document.getElementById('confirmInstallBtn');
    const dismissBtns = document.querySelectorAll('#installAppModal .btn-secondary, #installAppModal .btn-close');

    confirmInstallBtn.addEventListener('click', async () => {
        installModal.hide();
        deferredPrompt.prompt();

        const { outcome } = await deferredPrompt.userChoice;
        console.log(`User response to the install prompt: ${outcome}`);

        deferredPrompt = null;
    });

    dismissBtns.forEach(btn => {
        btn.addEventListener('click', () => {
        setInstallPromptDismissed();
        });
    });
    });

    // Trigger toast on successful install
    function showInstallToast() {
    const toastEl = document.getElementById('installToast');
    const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
    toast.show();
    }

    // Example usage after install
    confirmInstallBtn.addEventListener('click', () => {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then(choiceResult => {
        if (choiceResult.outcome === 'accepted') {
            console.log('User accepted install prompt');
            installModal.hide();
            showInstallToast();  // Show toast here
        } else {
            console.log('User dismissed install prompt');
        }
        deferredPrompt = null;
        });
    }
    });
</script> --}}


<!-- Install App Modal -->
{{-- <div class="modal fade" id="installAppModal" tabindex="-1" aria-labelledby="installAppModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="installAppModalLabel">Install Zopa App</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <img src="{{ asset('front/images/logo_red.png') }}" alt="@appName" width="80" class="mb-3">
        <p>Get a faster, smoother Zopa experience right on your phone — install our app now!</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Maybe later</button>
        <button type="button" class="btn btn-primary" id="confirmInstallBtn">Install App</button>
      </div>

    </div>
  </div>
</div>


<!-- Zopa Install Success Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
  <div id="installToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        ✅ Zopa app installed successfully!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div> --}}
@stack('scripts')
</body>
</html>
