@extends('layouts.app')
@section('title', 'Daily Meals - Zopa Food Drop')

@section('content')
<style>
    .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.2s;
    }
 .empty-state i {
        color: #adb5bd;
    }
    .empty-state p {
        font-size: 1.1rem;
    }
    .alert-info {
        background-color: #e7f3fe;
        border-left: 4px solid #0d6efd;
    }
</style>
<div class="container my-2">
    <x-flash-messages />

    @if(isset($mealsLeft))
        <div class="alert alert-info d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <i class="bi bi-wallet2 me-2"></i>
                <strong>Meal Balance:</strong> {{ $mealsLeft }} No{{ $mealsLeft == 1 ? '' : 's' }}.
            </div>
            <a href="{{ route('front.meal.plan') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa-solid fa-plus"></i> Top Up
            </a>
        </div>
    @endif
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            Daily Meals
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    {{-- @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif --}}

    <div class="mb-5">

        @if (count($todayOrders) > 0)
            @foreach ($todayOrders as $order)
                @include('partials.order_card', ['order' => $order])
            @endforeach
        @else
            <div class="empty-state text-center text-muted my-4">
                <i class="bi bi-calendar-x display-4 mb-2"></i>
                @if($hasLeaveToday)
                    <div class="alert alert-danger d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Day Off:</strong> You’re on leave today, so no daily meal will be delivered.
                        </div>
                    </div>
                @else
                    @if($mealsLeft>0)
                        <div class="alert alert-success d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Waiting for Update:</strong> Your daily meal is being processed and will be updated shortly.
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Empty Wallet:</strong> Your Meal balance is zero. Please top up to continue receiving meals..
                            </div>
                            <a href="{{ route('front.meal.plan') }}" class="btn btn-sm btn-outline-success">
                                <i class="fa-solid fa-headset text-success"></i> Buy a Meal Plan
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        @endif
        @if(($mealsLeft < Utility::WALLET_LOW_BALANCE) && ($mealsLeft != 0))
            <div class="alert alert-warning d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <strong>Low Meal Balance ({{ $mealsLeft }} left):</strong> Daily Meal will stop when your balance reaches zero.
                </div>
                <a href="{{ route('front.meal.plan') }}" class="btn btn-sm btn-outline-danger">
                    <i class="fa-solid fa-headset"></i> Buy a Meal Plan
                </a>
            </div>
        @endif
    </div>

</div>

<div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
    <div id="toastContainer"></div>
</div>

<style>
button .spinner-border {
    vertical-align: text-bottom;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cancel-order').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!confirm('Are you sure you want to cancel this order?')) {
                return;
            }

            const url = this.action;
            const token = this.querySelector('input[name="_token"]').value;
            const formEl = this;
            const button = formEl.querySelector('button');

            const originalHtml = button.innerHTML;
            button.disabled = true;
            button.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Cancelling...`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);  // reload after toast
                } else {
                    showToast(data.message, 'error');
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                }
            })
            .catch(error => {
                showToast('Something went wrong.', 'error');
                button.disabled = false;
                button.innerHTML = originalHtml;
            });
        });
    });
});
</script>

<script>
// Simple toast generator
function showToast(message, type = 'success') {
    const toastId = 'toast-' + Date.now();
    const icon = type === 'success' ? 'check-circle-fill text-success' : 'exclamation-triangle-fill text-danger';
    const bgClass = type === 'success' ? 'bg-success text-white' : 'bg-danger text-white';

    const toastHtml = `
    <div id="${toastId}" class="toast ${bgClass}" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-${icon} me-2"></i> ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>`;

    const container = document.getElementById('toastContainer');
    container.insertAdjacentHTML('beforeend', toastHtml);

    const toastEl = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastEl);
    toast.show();

    // Remove toast from DOM after hidden
    toastEl.addEventListener('hidden.bs.toast', () => {
        toastEl.remove();
    });
}
</script>
@endpush


