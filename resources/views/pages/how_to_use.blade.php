@extends('layouts.app')

@section('title', 'How to Use Zopa - ' . config('app.name'))

@section('content')
<div class="container my-4">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            How to Use @appName
            {{ app()->getLocale() }}
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #27ae60; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="row">
        @include('partials.how_to_use_content')
    </div>
</div>
@endsection
