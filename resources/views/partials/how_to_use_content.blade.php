<div class="col-md-12 p-4 lh-lg">

        <h5 class="text-secondary mb-3"><i class="fa-solid fa-circle-user"></i> Register or Log in</h5>
        <p>Visit the login page and enter your <strong>registered phone number</strong> and <strong>password</strong>. You must be <strong>approved and active</strong> to access your dashboard.</p>

        <hr>

        <h5 class="text-secondary mb-3"><i class="fa-solid fa-cart-plus"></i> Purchase Meals</h5>
        <p>You can purchase meals in two ways:</p>
        <ul>
            <li><strong>Buy a Plan:</strong> Go to <em>Zopa Meals → Buy A Plan</em>. Select a prepaid plan and pay online or via Cash on Delivery. Meals will be added to your <strong>Meal Wallet</strong>.</li>
            <li><strong>Buy Single Meal:</strong> Go to <em>Zopa Meals → Buy Single</em>. Select the meal, quantity, and pay for a one-time order.</li>
        </ul>
        <p><small><i>Note: Even single meal purchases are assigned in the morning like prepaid plans. Make sure you haven’t applied for leave.</i></small></p>

        <hr>

        <h5 class="text-secondary mb-3"><i class="fa-solid fa-utensils"></i> Purchase Add-ons (Optional)</h5>
        <p>Enhance your daily meals with extra items. Visit <em>Add-ons</em> to purchase side dishes like beef fry or fish fry. These are delivered along with your main meal.</p>
        <p><small><i>Note: Add-on purchases cannot be canceled after {{ $lastOrderTime }} on the day of delivery.</i></small></p>

        <hr>

        <h5 class="text-secondary mb-3"><i class="fa-solid fa-calendar-day"></i> How Daily Meals Are Assigned</h5>
        <p>Each morning, the system will:</p>
        <ul>
            <li>Check your <strong>Meal Wallet</strong> balance.</li>
            <li>If you have <strong>at least 1 meal</strong> and <strong>have not applied for leave</strong>, a meal is auto-assigned.</li>
            <li><strong>No meal is assigned on Sundays.</strong></li>
            <li>Daily meals appear under <em>Daily Orders</em>.</li>
        </ul>

        <hr>

        <h5 class="text-secondary mb-3"><i class="fa-solid fa-plus"></i> Request an Extra Meal</h5>
        <p>On the <em>My Meals</em> page, click <strong>“Request Extra Meal”</strong>. Enter how many extra meals you want. These will be deducted from your wallet and added to your Daily Orders.</p>

        <hr>

        <h5 class="text-secondary mb-3"><i class="fa-solid fa-calendar-xmark"></i> Apply for a Meal Leave</h5>
        <p>Not eating on a certain day? Go to <em>My Leaves</em> and mark the day off.</p>
        <ul>
            <li>You can apply leave up to <strong>{{ Utility::MAX_LEAVE_DAYS_AHEAD }}</strong> days in advance.</li>
            <li>You <strong>cannot mark leave for past dates</strong>.</li>
            <li>Today's leave must be applied <strong>before {{ $lastOrderTime }}</strong>.</li>
            <li>Leaves <strong>cannot be canceled</strong> after the cutoff time on the same day.</li>
        </ul>

        <hr>

        <h5 class="text-secondary mb-3"><i class="fa-solid fa-wallet"></i> Track Orders & Wallet</h5>
        <ul>
            <li>Check <strong>Daily Orders</strong> for your upcoming and past meals.</li>
            <li>Your <strong>Meal Wallet balance</strong> is always visible in the top-right menu.</li>
            <li>Past transactions are listed under <em>My Purchases</em>.</li>
            <li>To add more meals to your wallet, simply purchase a new plan under <em>Buy A Plan</em>.</li>
            <li>After purchasing a plan or meal, you can <strong>view, print, or share</strong> your payment confirmation from the success page or My Purchases.</li>
        </ul>

        <hr>

        <h5 class="text-secondary mb-3"><i class="fa-solid fa-truck"></i> Track Delivery Status</h5>
        <ul>
            <li>Go to <em>Daily Orders</em> to check the delivery status of your meals.</li>
            <li>If a meal is not delivered, you may see a tooltip with the reason.</li>
            <li>Add-on deliveries are also shown under the same section.</li>
        </ul>

        <hr>

        <h5 class="text-secondary mb-3"><i class="fa-solid fa-gear"></i> Manage Your Profile</h5>
        <ul>
            <li>Update your personal info under <em>My Profile</em>.</li>
            <li>Check past purchases and meal leaves.</li>
            <li>Securely logout when done.</li>
        </ul>

        <hr>

        <h5 class="text-secondary mb-3"><i class="fa-solid fa-comment-dots"></i> Send Us Feedback</h5>
        <p>Have suggestions? Visit <em>Feedbacks</em> to leave us a message.</p>

        <hr>

        <h5 class="text-secondary mb-3"><i class="fa-solid fa-circle-question"></i> Troubleshooting</h5>
        <ul>
            <li><strong>Can't log in?</strong> Make sure your account is approved and active. Contact us if needed.</li>
            <li><strong>Meal not assigned today?</strong> Check if your wallet has balance or if you applied a leave.</li>
            <li><strong>Missed the cutoff time?</strong> Orders, leaves, and add-on changes can't be made after <strong>{{ $lastOrderTime }}</strong>.</li>
        </ul>

        <hr>

        <h5 class="text-secondary mb-3"><i class="fa-solid fa-lightbulb"></i> Tips</h5>
        <ul>
            <li>Use the <a href="{{ route('site_map') }}">Site Map</a> for quick access on the go.</li>
            <li>Keep your Meal Wallet loaded to avoid missing meals.</li>
            <li>Don't forget to apply leaves before <strong>{{ $lastOrderTime }}</strong>.</li>
        </ul>

        <a class="btn btn-zopa" href="{{ route('how_to_use_pdf') }}">
            <i class="fas fa-download me-1"></i> Download PDF
        </a>
    </div>
