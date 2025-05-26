<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Meal Plans | Zopa Food Drop'); ?></title>
    <!-- Bootstrap CSS -->
    <link href="<?php echo e(asset('front/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('front/css/global.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('front/font-awesome/css/all-6.0.0.min.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

    <!-- Standard favicon -->
    <link rel="icon" href="<?php echo e(asset('favicon/favicon.ico')); ?>" type="image/x-icon">

    <!-- For modern browsers -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('favicon/favicon-32x32.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('favicon/favicon-16x16.png')); ?>">

    <!-- Apple Touch Icon (iPhone/iPad) -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('favicon/apple-touch-icon.png')); ?>">

    <!-- Android Chrome Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo e(asset('favicon/android-chrome-192x192.png')); ?>">
    <link rel="icon" type="image/png" sizes="512x512" href="<?php echo e(asset('favicon/android-chrome-512x512.png')); ?>">

    <!-- Manifest (for Android and PWA) -->
    <link rel="manifest" href="<?php echo e(asset('favicon/site.webmanifest')); ?>">

    <!-- Microsoft Tiles -->
    <meta name="msapplication-TileColor" content="#ec1d23">
    <meta name="msapplication-TileImage" content="<?php echo e(asset('favicon/android-chrome-192x192.png')); ?>">

    <!-- Theme Color (browser UI) -->
    <meta name="theme-color" content="#ec1d23">
    <style>
        .required-label::after {
            content: " *";
            color: red;
        }
        .invalid {
            border: 2px solid red !important;
        }

        /* Zopa Install Modal Styles */
        #installAppModal .modal-content {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }

        #installAppModal .modal-header {
        background-color: #d62f2f; /* Zopa red */
        color: #fff;
        border-bottom: none;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        }

        #installAppModal .modal-body {
        padding-top: 1rem;
        padding-bottom: 1rem;
        }

        #installAppModal .modal-footer {
        border-top: none;
        justify-content: space-between;
        }

        #installAppModal .btn-primary {
        background-color: #d62f2f; /* Zopa red */
        border-color: #d62f2f;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        }

        #installAppModal .btn-primary:hover {
        background-color: #b72828;
        border-color: #b72828;
        }

        #installAppModal .btn-secondary {
        color: #6c757d;
        background: none;
        border: none;
        text-decoration: underline;
        padding: 0;
        }

        #installAppModal .btn-secondary:hover {
        color: #495057;
        }

        /* Zopa Modal Animation */
        @keyframes zopaModalShow {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.98);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        }

        #installAppModal .modal-dialog {
        transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        }

        #installAppModal.show .modal-dialog {
        animation: zopaModalShow 0.35s ease forwards;
        }

        /* Zopa Toast Styles */
        #installToast {
        background-color: #198754; /* Bootstrap success green */
        border-radius: 0.75rem;
        box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.15);
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<?php echo $__env->yieldContent('content'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

</script>

<script>
//   if ('serviceWorker' in navigator) {
//     navigator.serviceWorker.register('<?php echo e(asset("front/js/sw.js")); ?>').then(function(reg) {
//       console.log('Zopa SW registered:', reg);
//     }).catch(function(error) {
//       console.log('Zopa SW registration failed:', error);
//     });
//   }
</script>

<script>
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

    window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;

    // Only show modal if not installed
    if (!isAppInstalled()) {
        const installModal = new bootstrap.Modal(document.getElementById('installAppModal'));
        // Wait 5 seconds before showing
        setTimeout(() => installModal.show(), 3000);

        const confirmInstallBtn = document.getElementById('confirmInstallBtn');

        confirmInstallBtn.addEventListener('click', async () => {
        installModal.hide();
        deferredPrompt.prompt();

        const { outcome } = await deferredPrompt.userChoice;
        console.log(`User response to the install prompt: ${outcome}`);

        deferredPrompt = null;
        });
    }
    });
</script>

<?php echo $__env->yieldPushContent('scripts'); ?>

<!-- Install App Modal -->
<div class="modal fade" id="installAppModal" tabindex="-1" aria-labelledby="installAppModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="installAppModalLabel">Install Zopa App</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <img src="<?php echo e(asset('front/images/logo_red.png')); ?>" alt="Zopa Food Drop" width="80" class="mb-3">
        <p>Get a faster, smoother Zopa experience right on your phone — install our app now!</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Maybe later</button>
        <button type="button" class="btn btn-primary" id="confirmInstallBtn">Install App</button>
      </div>

    </div>
  </div>
</div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\layouts\out.blade.php ENDPATH**/ ?>