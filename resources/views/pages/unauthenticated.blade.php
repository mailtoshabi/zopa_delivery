@extends('layouts.app')

@section('title', 'Access Denied - ' . config('app.name'))

@section('content')
<div class="container mt-5">

    {{-- Page Title --}}
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            Access Denied
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    {{-- Card --}}
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-lock fa-4x text-danger"></i>
                    </div>
                    <h4 class="mb-3">You must be logged in to access this page</h4>
                    <p class="text-muted mb-4">
                        Please log in with your account to continue, or return to our homepage.
                    </p>

                    {{-- Action Buttons --}}
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                        <a href="{{ url('/') }}" class="btn btn-outline-primary btn-lg px-4">
                            <i class="fas fa-home me-2"></i> Home
                        </a>
                        <a href="{{ route('customer.login') }}" class="btn btn-success btn-lg px-4">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </a>
                    </div>
                </div>
            </div>

            {{-- Links Section --}}
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-uppercase fw-bold mb-3">Quick Links</h6>
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="{{ route('about_us') }}">{{ __('messages.menu.about') }}</a></li>
                        <li class="list-inline-item"><a href="{{ route('how_to_use') }}">{{ __('messages.menu.how_to_use') }}</a></li>
                        {{-- <li class="list-inline-item"><a href="{{ route('feedbacks') }}">{{ __('messages.menu.feedbacks') }}</a></li> --}}
                        <li class="list-inline-item"><a href="{{ route('payment_terms') }}">{{ __('messages.menu.payment_terms') }}</a></li>
                        <li class="list-inline-item"><a href="{{ route('privacy_policy') }}">{{ __('messages.menu.privacy') }}</a></li>
                        <li class="list-inline-item"><a href="{{ route('support') }}">{{ __('messages.menu.support') }}</a></li>
                        <li class="list-inline-item"><a href="{{ route('faq') }}">{{ __('messages.menu.faq') }}</a></li>
                        <li class="list-inline-item"><a href="{{ route('site_map') }}">{{ __('messages.menu.site_map') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
