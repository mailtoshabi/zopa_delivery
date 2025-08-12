<?php $__env->startSection('title', 'Privacy Policy - ' . config('app.name')); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            Privacy Policy
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>
    <p class="text-center text-muted mt-3">Your privacy is important to us at <?php echo config('app.name'); ?></p>

    <section class="mb-5">
        <h3>1. Information We Collect</h3>
        <p>We collect personal information such as your name, contact details, delivery address, and payment information when you place an order. Additionally, we may collect usage data, such as browsing history and preferences, to enhance our services.</p>
    </section>

    <section class="mb-5">
        <h3>2. How We Use Your Information</h3>
        <ul>
            <li>To process and fulfill your meal orders.</li>
            <li>To provide customer support and respond to inquiries.</li>
            <li>To improve our website and personalize user experience.</li>
            <li>To send updates, promotional offers, and notifications.</li>
        </ul>
    </section>

    <section class="mb-5">
        <h3>3. Data Security</h3>
        <p>We take necessary measures to protect your personal information from unauthorized access, alteration, or disclosure. Our website uses secure encryption technologies to safeguard payment transactions.</p>
    </section>

    <section class="mb-5">
        <h3>4. Sharing of Information</h3>
        <p>We do not sell or rent your personal data. However, we may share necessary information with trusted third-party service providers for payment processing, delivery services, and marketing efforts.</p>
    </section>

    <section class="mb-5">
        <h3>5. Cookies & Tracking Technologies</h3>
        <p>We use cookies and similar tracking technologies to analyze website traffic, enhance user experience, and serve relevant ads. You can manage your cookie preferences in your browser settings.</p>
    </section>

    <section class="mb-5">
        <h3>6. Your Rights & Choices</h3>
        <p>You have the right to access, update, or delete your personal data. If you wish to opt out of marketing communications, you can update your preferences in your account settings.</p>
    </section>

    <section class="mb-5">
        <h3>7. Changes to Privacy Policy</h3>
        <p>We reserve the right to update our Privacy Policy as necessary. Any changes will be posted on this page, and we encourage users to review it periodically.</p>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\privacy_policy.blade.php ENDPATH**/ ?>