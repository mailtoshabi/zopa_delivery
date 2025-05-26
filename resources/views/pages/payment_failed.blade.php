@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h2 class="text-danger">Payment Failed</h2>
    <p>{{ session('error') ?? 'There was a problem processing your payment.' }}</p>
    <a href="{{ route('front.meal.plan') }}" class="btn btn-primary">Go Back Meal Plans</a>
</div>
@endsection
