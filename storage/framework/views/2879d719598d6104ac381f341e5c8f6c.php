<?php $__env->startSection('title', 'Support - Zopa Food Drop'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            Support
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>
    <p class="text-center text-muted mt-3">We’re here to assist you with any queries or concerns.</p>

    <section class="mb-5">
        <h3>Contact Us</h3>
        <p>If you need assistance, please reach out to us through the following methods:</p>
        <ul>
            <li><strong>Email:</strong> support@zopa.in</li>
            <li><strong>Phone:</strong> +91 XXXXXXXXXX</li>
            <li><strong>Live Chat:</strong> Available on our website during business hours.</li>
        </ul>
    </section>

    <section class="mb-5">
        <h3>Frequently Asked Questions (FAQs)</h3>
        <p>Before reaching out, you may find the answers to your questions in our <a href="<?php echo e(route('faq')); ?>">FAQs section</a>.</p>
    </section>

    <section class="mb-5">
        <h3>Order & Subscription Support</h3>
        <p>If you need help with your order or subscription, visit your <a href="<?php echo e(route('customer.profile')); ?>">Profile</a> page to manage your meals.</p>
    </section>

    <section class="mb-5">
        <h3>Technical Support</h3>
        <p>If you're facing issues with our website or mobile app, please send us an email with a detailed description of the problem.</p>
    </section>

    <section>
        <h3>Business Hours</h3>
        <p>We are available to assist you during the following hours:</p>
        <ul>
            <li><strong>Monday - Saturday:</strong> 9:00 AM - 5:30 PM</li>
            
        </ul>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\support.blade.php ENDPATH**/ ?>