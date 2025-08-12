@extends('layouts.app')

@section('title', 'Buy A Meal - ' . config('app.name'))

@section('content')
<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">Buy A Meal</h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>
    @if(isset($meal))
    <div class="row d-flex justify-content-center align-items-center">
        {{-- @foreach($meals as $meal) --}}
            <div class="col-sm-6 mb-3">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="card-title">{{ $meal->name }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ asset('front/images/meals.png') }}" alt="@appName" class="img-fluid d-block mx-auto">
                        </div>
                        <ul class="list-group mt-3">
                            @foreach($meal->ingredients as $ingredient)
                                <li class="list-group-item">{{ $ingredient->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @if($meal->remarks->isNotEmpty())
                    <div class="card-body">
                        <ul class="list-group mt-3">
                            @foreach($meal->remarks as $remark)
                                <li class="list-group-item">{{ $remark->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="card-header d-flex justify-content-center align-items-center">
                        <a href="{{ route('meal.purchase', encrypt($meal->id)) }}" class="btn btn-zopa me-2 makeButtonDisable">
                            <b>Buy @ <i class="inr-size fa-solid fa-indian-rupee-sign"></i>{{ number_format($meal->price, 2) }}</b>
                        </a>

                    </div>
                </div>
            </div>
        {{-- @endforeach --}}
    </div>
    @endif
</div>
@endsection
