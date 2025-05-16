<?php $__env->startSection('content'); ?>
<div style="font-family: sans-serif; color: #333;">
    <hr style="margin: 10px 0;">

    <h3 style="color: #e60000;">Important Update</h3>
    <p><strong>Cutoff time for daily meals and changes:</strong><br>
    The cutoff time is <strong><?php echo e(App\Helpers\FileHelper::convertTo12Hour(Utility::CUTOFF_TIME)); ?></strong>. You must request meals, leaves, or changes <strong>before <?php echo e(App\Helpers\FileHelper::convertTo12Hour(Utility::CUTOFF_TIME)); ?> on the same day</strong>.</p>

    <h3 style="color: #e60000;">How to Use Zopa Food Drop</h3>
    <ol>
        <li><strong>Log in to Your Customer Account</strong><br>
            Use your registered phone number and password. Only approved and active accounts can access the dashboard.
        </li>

        <li><strong>Buy Meals</strong><br>
            <ul>
                <li><strong>Buy a Plan:</strong> Prepay and get meals in your Meal Wallet.</li>
                <li><strong>Buy Single Meal:</strong> Pay for one-time meals as needed.</li>
            </ul>
        </li>

        <li><strong>Daily Meal Allocation</strong><br>
            Meals are auto-assigned each morning if:<br>
            You have at least 1 meal in your wallet<br>
            You have not applied for leave<br>
            No meals on Sundays.
        </li>

        <li><strong>Request Extra Meals</strong><br>
            Request extra meals (for guests or special needs) <strong>before <?php echo e(App\Helpers\FileHelper::convertTo12Hour(Utility::CUTOFF_TIME)); ?></strong> on the same day via the My Meals page.
        </li>

        <li><strong>Apply for Meal Leave</strong><br>
            Mark off days you don’t want a meal.<br>
            - Leaves can be applied up to <strong><?php echo e(Utility::MAX_LEAVE_DAYS_AHEAD); ?></strong> days ahead.<br>
            - Cannot cancel after <strong><?php echo e(App\Helpers\FileHelper::convertTo12Hour(Utility::CUTOFF_TIME)); ?></strong> on the day.
        </li>

        <li><strong>Track Orders & Wallet</strong><br>
            View upcoming meals in Daily Orders and track Meal Wallet balance.
        </li>

        <li><strong>Profile & Settings</strong><br>
            Update personal info, check past purchases, and logout securely.
        </li>

        <li><strong>Feedback</strong><br>
            Leave suggestions through the Feedback section anytime.
        </li>
    </ol>

    <h3 style="color: #e60000;">Extra Tips</h3>
    <ul>
        <li>Use the <strong>Site Map</strong> for quick navigation</li>
        <li>Keep wallet topped up to avoid missing meals</li>
        <li>Always apply leaves or request extras <strong>before <?php echo e(App\Helpers\FileHelper::convertTo12Hour(Utility::CUTOFF_TIME)); ?></strong></li>
    </ul>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pdf', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\pages\how_to_use_pdf.blade.php ENDPATH**/ ?>