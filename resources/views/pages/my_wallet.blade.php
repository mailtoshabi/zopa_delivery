@extends('layouts.app')

@section('title', 'My Wallet - Zopa Food Drop')
@section('content')
<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            My Wallets
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="row">

        {{-- Meal Wallet --}}
        <div class="col-sm-12 mb-3">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h4 class="mb-3">Meal Wallet</h4>
                </div>
                <div class="card-body">
                    @if($meal_wallet->status == 0)
                        <div class="alert alert-warning d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Inactive Wallet:</strong> Your meal wallet is currently inactive. Daily meals will not be delivered.
                            </div>
                            <a href="{{ route('support') }}" class="btn btn-sm btn-outline-danger">
                                <i class="fa-solid fa-headset"></i> Contact Support
                            </a>
                        </div>
                    @endif

                    <small class="text-muted d-block mt-1 mb-3">
                        If you have meal balance, Daily Meal gets auto-delivered — no need to set a reminder or do anything.<br>
                        Just mark leave if you want to skip a day. Sundays are already off!
                    </small>

                    <div class="row d-flex align-items-center" style="width: 100%;">
                        <div class="col-sm-12 col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                {{-- Meal Image --}}
                                <img src="{{ asset('front/images/meals.png') }}"
                                    alt="Meals"
                                    class="rounded me-3 shadow-sm"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                                <span class="me-2 {{ $meal_wallet->status ? 'text-dark' : 'text-muted' }}">Meals</span>
                                <span class="badge {{ $meal_wallet->status ? 'bg-success' : 'bg-secondary' }} rounded-pill">{{ $meal_wallet ? $meal_wallet->quantity : 0 }} left</span>
                                @if($meal_wallet->quantity < Utility::WALLET_LOW_BALANCE)
                                    <span class="badge bg-danger rounded-pill">Low Balance</span><span>&nbsp;&nbsp;<a href="{{ route('front.meal.plan') }}" >Buy Meals</a></span>
                                @endif
                            </div>
                        </div>

                        {{-- <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">
                            <a href="javascript:void(0);" class="btn btn-zopa extra-meal-btn">
                                <i class="fa-solid fa-plus-circle"></i> Request Extra Meal
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Addon Wallet --}}
        <div class="col-sm-12 mb-3">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h4 class="mb-3">Addons Wallet</h4>
                </div>
                @if($addon_wallet->count() > 0)
                    <div class="card-body">
                        <small class="text-muted d-block mt-1 mb-2">
                            If you have addon balance and it’s on, we’ll include it with your Daily Meal.
                            Don’t want it? Just switch it off!
                        </small>

                        @if($addon_wallet->where('status', 0)->count() > 0)
                            <div class="alert alert-warning d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Note:</strong> Suspended addons will not be delivered.
                                    <br class="d-sm-none">
                                    To activate, <a href="{{ route('support') }}">contact support</a>.
                                </div>
                            </div>
                        @endif

                        <ul class="list-group">
                            @foreach($addon_wallet as $item)
                                <li class="list-group-item d-flex align-items-center justify-content-between">
                                    <div class=" align-items-center">
                                        @if($item->addon->image_filename)
                                        {{-- Addon Image --}}
                                        <img src="{{ Storage::url('addons/' . $item->addon->image_filename) }}"
                                            alt="{{ $item->addon->name }}"
                                            class="rounded me-3 shadow-sm"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif

                                        {{-- Addon Name --}}
                                        <span class="{{ $item->status ? 'text-dark' : 'text-muted' }}"><strong>{{ $item->addon->name }}</strong></span>
                                        @if($item->addon->description)
                                            <small>&nbsp;<i class="fa-solid fa-circle-info text-zopa"
                                            data-bs-toggle="tooltip"
                                            data-bs-html="true"
                                            data-bs-placement="top"
                                            title="{!! nl2br(e($item->addon->description)) !!}"></i></small>
                                        @endif

                                        @if($item->status == 0)
                                            <span class="badge bg-danger text-white ms-2">Suspended</span>
                                        @else
                                            <span class="addon-status-badge ms-2" data-id="{{ $item->id }}">
                                                @if($item->is_on == 0)
                                                    <span class="badge bg-warning text-dark">Inactive</span>
                                                @endif
                                            </span>


                                            @if($item->quantity < Utility::WALLET_LOW_BALANCE)
                                                <span class="badge bg-danger rounded-pill">Low Balance</span><span>&nbsp;&nbsp;<a href="{{ route('front.show.addons') }}" >Buy Addons</a></span>
                                            @endif
                                        @endif
                                    </div>

                                    {{-- Quantity Badge and Toggle --}}
                                    <div class="d-flex align-items-center">
                                        <span class="badge {{ $item->status ? 'bg-success' : 'bg-secondary' }} rounded-pill me-3">{{ $item->quantity }} left</span>

                                        {{-- Toggle switch only --}}
                                        @if($item->status == 1)
                                            <div class="form-check form-switch">
                                                <input class="form-check-input addon-status-toggle"
                                                    type="checkbox"
                                                    data-id="{{ $item->id }}"
                                                    {{ $item->is_on ? 'checked' : '' }}>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="card-body text-center">
                        <p class="text-muted mt-3">
                            You haven't purchased any addons yet. Get started by choosing from our delicious extras!
                        </p>

                        <a href="{{ route('front.show.addons') }}" class="btn btn-zopa makeButtonDisable">
                            <i class="fa-solid fa-plus-circle"></i>&nbsp;&nbsp;Buy Addons
                        </a>
                    </div>
                @endif
            </div>
        </div>


        {{-- Extra Meal Action --}}
        <div class="col-sm-12 mb-3">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h4 class="mb-3">Monthly Leaves Limit</h4>
                </div>
                <div class="card-body text-center">
                    <a href="{{ route('customer.leave.index') }}" class="btn btn-zopa makeButtonDisable">
                        <i class="fa-solid fa-calendar-xmark"></i> Mark Leaves
                    </a>
                    <p class="pt-2"><small>
                        You currently have <strong>{{ $activeLeaveCount }}</strong> of <strong>{{ $maxActiveLeaves }}</strong> active leaves.
                        Contact <a href="{{ route('support') }}">Support</a> for long-term leaves.
                    </small>
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.addon-status-toggle');

        toggles.forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const walletId = this.dataset.id;
                const isOn = this.checked ? 1 : 0;

                fetch('{{ route('addonWallet.toggleStatus') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ wallet_id: walletId, is_on: isOn })
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        const badgeContainer = document.querySelector(`.addon-status-badge[data-id="${walletId}"]`);
                        if (badgeContainer) {
                            if (isOn === 1) {
                                badgeContainer.innerHTML = '';
                            } else {
                                badgeContainer.innerHTML = '<span class="badge bg-warning text-dark">Inactive</span>';
                            }
                        }
                    } else {
                        alert('Failed to update toggle.');
                        toggle.checked = !isOn;
                    }
                })
                .catch(() => {
                    alert('Something went wrong. Try again.');
                    toggle.checked = !isOn;
                });
            });
        });
    });
</script>
@endpush

