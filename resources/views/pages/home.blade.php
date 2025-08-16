@extends('layouts.app')

@section('title', 'Daily Tiffin & Meal Delivery Service in Kerala | ' . config('app.name'))

@section('content')
<div class="container my-4">
    <div class="text-center mb-4">
        <h1 class="position-relative d-inline-block px-4 py-2">
            {{ __('messages.welcome') }}
        </h1>
        <div class="mt-2" style="width: 200px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="row align-items-center my-5">
        <div class="col-md-6 text-center mb-4 mb-md-0">
            {{-- <img src="{{ asset('front/images/home-meal.png') }}" alt="Delicious Meals" class="img-fluid rounded shadow"> --}}
            <video class="w-100 rounded shadow" autoplay muted loop playsinline poster="{{ asset('front/images/home-meal-poster.jpeg') }}">
                <source src="{{ asset('front/videos/home-meal.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div class="col-md-6">
            <h3>{{ __('messages.feature') }}</h3>
            <p class="mt-3">
                <strong>@appName</strong> {{ __('messages.about_short') }}
            </p>
            <a href="{{ route('front.meal.plan') }}" class="btn btn-zopa px-4 py-2 mt-3">
                <b>{{ __('messages.meal_plans.explore') }}</b>
            </a>
        </div>
    </div>
    @if(app()->getLocale() === 'ml')
    @include('partials.why_choose_us_ml')
    @else
    @include('partials.why_choose_us')
    @endif
</div>
@php
    use App\Http\Utilities\Utility;

    if(auth('customer')->check()) {
        $lastOrderTime = App\Helpers\FileHelper::convertTo12Hour(auth('customer')->user()->cutoff_time); // From accessor
    } else {
        $lastOrderTime = App\Helpers\FileHelper::convertTo12Hour(Utility::CUTOFF_TIME);
    }
    @endphp
@include('partials.how_to_use_modal', ['lastOrderTime' => $lastOrderTime])
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        if (!sessionStorage.getItem('howToUseShown')) {
            $('#howToUseModal').modal('show');
            sessionStorage.setItem('howToUseShown', 'true');
        }
    });
</script>
@endpush
