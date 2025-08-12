@php
    $customer = Auth::guard('customer')->user();

    $walletCount = 0;
    $mealCount = 0;
    $addonCount = 0;
    $totalCartCount = 0;
    $profileImage = '';

    if ($customer) {
        $walletCount = $customer->mealWallet->quantity ?? 0;

        $mealCart = session('meal_cart', []);
        $addonCart = session('addon_cart', []);
        $mealCount = collect($mealCart)->sum('quantity');
        $addonCount = collect($addonCart)->sum('quantity');
        $totalCartCount = $mealCount + $addonCount;

        $profileImage = $customer->image_filename
            ? Storage::url(App\Models\Customer::DIR_PUBLIC . '/' . $customer->image_filename)
            : 'https://ui-avatars.com/api/?name='.$customer->name.'&background=ec1d23&color=fff';
    }
    if (Auth::guard('customer')->check()) {
        $mess_categories = App\Models\MessCategory::withActiveMealsForKitchen();
    }else {
        $mess_categories = App\Models\MessCategory::where('status',Utility::ITEM_ACTIVE)->orderBy('display_order','asc')->get();
    }


@endphp

<!-- Navigation Bar -->
<nav class="navbar navbar-light bg-light">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="{{ route('index') }}">
            <img src="{{ asset('front/images/logo.png') }}" alt="@appName" class="logo">
        </a>
        <span class="menu-toggle" onclick="toggleMenu()">
            <i class="fa-solid fa-bars"></i>
        </span>
        <div class="desktop-menu">
            <ul class="navbar-nav d-flex flex-row gap-5">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index')}}"><i class="fa-solid fa-home"></i> {{ __('messages.menu.home') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('customer.daily_meals') }}">
                        <i class="fa-solid fa-utensils"></i>&nbsp;&nbsp;{{ __('messages.menu.daily_meals') }}

                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="ordersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-concierge-bell"></i>&nbsp;&nbsp;{{ __('messages.menu.zopa_mess') }}</a>
                    <ul class="dropdown-menu" aria-labelledby="ordersDropdown">
                        @if ($customer)<li><a class="dropdown-item" href="{{ route('my.wallet') }}"><i class="fa-solid fa-wallet"></i>&nbsp;&nbsp;{{ __('messages.menu.wallet') }}</a></li>@endif
                        @foreach ($mess_categories as $category)
                            <li><a class="dropdown-item" href="{{ route('front.meal',$category->slug) }}"><i class="fa-solid fa-receipt"></i>&nbsp;&nbsp;{{ $category->name }}</a></li>
                        @endforeach
                        {{-- <li><a class="dropdown-item" href="{{ route('front.meal.plan') }}"><i class="fa-solid fa-receipt"></i>&nbsp;&nbsp;{{ __('messages.menu.buy_plan') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('front.meal.single') }}"><i class="fa-solid fa-shopping-basket"></i>&nbsp;&nbsp;{{ __('messages.menu.buy_single') }}</a></li> --}}
                        <li><a class="dropdown-item" href="{{ route('front.show.addons') }}"><i class="fa-solid fa-plus-circle"></i>&nbsp;&nbsp;{{ __('messages.menu.buy_addons') }}</a></li>
                        @if ($customer && ($customer->type === Utility::CUSTOMER_TYPE_IND))<li><a class="dropdown-item extra-meal-btn" href="javascript:void(0);"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;{{ __('messages.menu.request_extra') }}</a></li>@endif
                    </ul>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('feedbacks')}}"><i class="fa-solid fa-comments"></i> {{ __('messages.menu.feedbacks') }}</a>
                </li> --}}
                @if($customer)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-user"></i> {{ __('messages.menu.my_account') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
                            {{-- <li><hr class="dropdown-divider mt-0 mb-0"></li> --}}
                            {{-- <li class="px-3">
                                <div class="d-flex gap-2">
                                    <a><small>Language</small></a>
                                    <a href="{{ route('front.set.language', 'en') }}" class="lang-box" data-bs-toggle="tooltip" data-bs-placement="top" title="English">EN</a>
                                    <a href="{{ route('front.set.language', 'ml') }}" class="lang-box" data-bs-toggle="tooltip" data-bs-placement="top" title="Malayalam">ML</a>
                                </div>
                            </li> --}}
                            {{-- <li><hr class="dropdown-divider mt-0 mb-0"></li> --}}

                            <li class="dropdown-header fw-bold text-zopa pb-0">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $profileImage }}" alt="Profile" class="rounded-circle" width="30" height="30">
                                    <div>
                                        {{ $customer->name }}<br>
                                        <small class="text-muted">{{ $customer->phone }}</small>
                                    </div>
                                </div>
                            </li>

                            <li class="dropdown-header fw-bold text-dark pt-0">
                                <small><a href="{{ route('my.wallet') }}" class="text-dark">{{ __('messages.menu.meal_wallet') }}:
                                    {{-- @if($walletCount > 0) --}}
                                            {{ $walletCount }}
                                    {{-- @endif --}}
                                </a>
                                </small></li>
                            <li><hr class="dropdown-divider mt-0 mb-0"></li>
                            <li><a class="dropdown-item" href="{{ route('customer.leave.index')}}"><i class="fa-solid fa-calendar-xmark"></i>&nbsp;&nbsp;{{ __('messages.menu.leaves') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.purchases')}}"><i class="fa-solid fa-receipt"></i>&nbsp;&nbsp;{{ __('messages.menu.purchases') }}</a></li>
                            @if ($customer->type === Utility::CUSTOMER_TYPE_INST)
                                <li><a class="dropdown-item" href="{{ route('customer.quantity-overrides.index') }}"><i class="fa-solid fa-sort-numeric-up-alt"></i>&nbsp;&nbsp;{{ __('messages.menu.my_quantity') ?? 'My Quantity' }}</a></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('customer.extra_meals')}}"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;{{ __('messages.menu.extra_meals') }}</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('customer.profile') }}"><i class="fa-solid fa-user-pen"></i>&nbsp;&nbsp;{{ __('messages.menu.profile') }}</a></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-right-from-bracket"></i>&nbsp;&nbsp;{{ __('messages.menu.logout') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(!$customer)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.login')}}"><i class="fa-solid fa-user"></i> Signup/Login</a>
                    </li>
                @endif
                {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('cart.index') }}">
                        <i class="fa-solid fa-shopping-cart"></i>
                        @if($totalCartCount > 0)
                            <span class="top-0 start-100 translate-middle badge rounded-pill bg-warning">
                                {{ $totalCartCount }}
                            </span>
                        @endif
                    </a>
                </li> --}}
            </ul>
        </div>
    </div>
</nav>

@if($customer && (empty($customer->office_name) || empty($customer->location_name)) )
    <div class="alert alert-danger d-flex justify-content-between align-items-center mx-4 mt-4 mb-4">
        <div>
            <i class="bi bi-wallet2 me-2"></i>
            <strong>Complete Your Address</strong> to recieve the meals to your doorstep.
        </div>
        <a href="{{ route('customer.profile') }}?edit=true" class="btn btn-sm btn-outline-danger text-danger">
            <i class="fa-solid fa-pencil text-danger"></i> Complete your Address
        </a>
    </div>
@endif

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <span class="close-btn" onclick="toggleMenu()">&times;</span>
    @if ($customer)
    <div class="px-4 py-3 border-bottom">
        {{-- <div class="d-flex gap-2 ps-2">
            <a><small>Language</small></a>
            <a href="{{ route('front.set.language', 'en') }}" class="lang-box" data-bs-toggle="tooltip" data-bs-placement="top" title="English">EN</a>
            <a href="{{ route('front.set.language', 'ml') }}" class="lang-box" data-bs-toggle="tooltip" data-bs-placement="top" title="Malayalam">ML</a>
        </div> --}}
        <div class="fw-bold text-zopa d-flex align-items-center gap-2">
            <img src="{{ $profileImage }}" alt="Profile" class="rounded-circle" width="30" height="30">
            <div>
                {{ $customer->name }}<br>
                <small class="text-muted">{{ $customer->phone }}</small>
            </div>
        </div>
        <div class="text-muted small"><a href="{{ route('my.wallet') }}" class="text-dark">My Wallet: {{ $walletCount }}</a></div>
    </div>
    @endif
    <ul>
        {{-- <li><a href="{{ route('cart.index') }}"><i class="fa-solid fa-shopping-cart"></i> Cart
            @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
            @if($cartCount > 0)
                <span class=" top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $cartCount }}
                </span>
            @endif
        </a></li> --}}
        <li>
            <div class="d-flex gap-2 ps-2">
                <a href="{{ route('front.set.language', 'en') }}" class="lang-box" data-bs-toggle="tooltip" data-bs-placement="top" title="English">EN</a>
                <a href="{{ route('front.set.language', 'ml') }}" class="lang-box" data-bs-toggle="tooltip" data-bs-placement="top" title="Malayalam">ML</a>
            </div>
        </li>
        @if ($customer)
        <li><a href="{{ route('customer.daily_meals') }}"><i class="fa-solid fa-utensils"></i> Daily Meals</a></li>
        @endif
        <li>
            <a href="#" onclick="toggleSubmenu(event, 'zopaMealsSubmenu')">
                <i class="fa-solid fa-concierge-bell"></i> {{ __('messages.menu.zopa_mess') }}
                <i class="fa-solid fa-chevron-down float-end"></i> {{-- DOWN ARROW --}}
            </a>
            <ul class="submenu" id="zopaMealsSubmenu">
                @if ($customer)
                <li><a href="{{ route('my.wallet') }}"><i class="fa-solid fa-wallet"></i> {{ __('messages.menu.wallet') }}</a></li>
                @endif
                <li><a href="{{ route('front.meal.plan') }}"><i class="fa-solid fa-receipt"></i> {{ __('messages.menu.buy_plan') }}</a></li>
                <li><a href="{{ route('front.meal.single') }}"><i class="fa-solid fa-shopping-basket"></i> {{ __('messages.menu.buy_single') }}</a></li>
                <li><a href="{{ route('front.show.addons') }}"><i class="fa-solid fa-plus-circle"></i> {{ __('messages.menu.buy_addons') }}</a></li>
            </ul>
        </li>
        <li><a href="{{ route('feedbacks')}}"><i class="fa-solid fa-comments"></i> {{ __('messages.menu.feedbacks') }}</a></li>
        @if ($customer)
        <li>
            <a href="#" onclick="toggleSubmenu(event, 'zopaMealsSettings')">
                <i class="fa-solid fa-user"></i> Account
                <i class="fa-solid fa-chevron-down float-end"></i> {{-- DOWN ARROW --}}
            </a>
            <ul class="submenu" id="zopaMealsSettings">
                <li><a href="{{ route('customer.leave.index') }}"><i class="fa-solid fa-calendar-xmark"></i> {{ __('messages.menu.leaves') }}</a></li>
                <li><a href="{{ route('customer.purchases')}}"><i class="fa-solid fa-receipt"></i> {{ __('messages.menu.purchases') }}</a></li>
                @if ($customer->type === Utility::CUSTOMER_TYPE_INST)
                    <li><a href="{{ route('customer.quantity-overrides.index') }}"><i class="fa-solid fa-sort-numeric-up-alt"></i> {{ __('messages.menu.my_quantity') }}</a></li>
                @else
                    <li><a href="{{ route('customer.extra_meals')}}"><i class="fa-solid fa-plus"></i> {{ __('messages.menu.extra_meals') }}</a></li>
                @endif
                <li><a href="{{ route('customer.profile') }}"><i class="fa-solid fa-user-pen"></i> {{ __('messages.menu.profile') }}</a></li>
            </ul>
        </li>
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i> {{ __('messages.menu.logout') }}
            </a>
        </li>
        @endif
    </ul>
</div>

<form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
    @csrf
</form>
