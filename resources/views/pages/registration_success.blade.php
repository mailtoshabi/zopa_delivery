@extends('layouts.app')

@section('title', 'Registration Successful - ' . config('app.name'))

@section('content')
<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            Registration Successful
        </h2>
        <div class="mt-1" style="width: 180px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="alert alert-success text-center">
        <h4>Welcome to @appName!</h4>
        <p class="mt-3">Your account has been created successfully. You can now browse and buy meal plans tailored to your taste and schedule.</p>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('front.meal.plan') }}" class="btn btn-zopa px-4 py-2">
            <b>Browse Meal Plans</b>
        </a>
    </div>
</div>
@endsection

@push('scripts')
@endpush
