@extends('layouts.app')

@section('title', 'Buy Meal Plans - ' . config('app.name'))

@section('content')
<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            Buy {{ isset($mess_category) ? $mess_category->name : 'Meal Plans' }}
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    @if (session('success'))
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
    @endif

    <div class="row">
        @foreach($meals as $meal)
            <div class="col-sm-6 mb-3">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="card-title">{{ $meal->name }}</h4>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('front/images/meals.png') }}" alt="@appName" class="img-fluid d-block mx-auto mb-3" style="max-height:150px;">

                        <!-- Details Button triggers modal -->
                        <button type="button" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#mealDetailsModal{{ $meal->id }}">
                            View Details
                        </button>
                    </div>

                    <div class="card-footer d-flex justify-content-center align-items-center">
                        <a href="{{ route('meal.purchase', encrypt($meal->id)) }}"
                           class="btn btn-zopa me-2 makeButtonDisable">
                           @auth('customer')
                            <b>Buy @ <i class="inr-size fa-solid fa-indian-rupee-sign"></i>{{ number_format($meal->price, 2) }}</b>
                            @if($meal->quantity>1)
                            <small>₹{{ number_format($meal->price/$meal->quantity, 0) }}/each</small>
                            @endif
                            @endauth
                            @guest('customer')
                            <b>Buy {{ $meal->name }}</b>
                            @endguest
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="mealDetailsModal{{ $meal->id }}" tabindex="-1" aria-labelledby="mealDetailsModalLabel{{ $meal->id }}" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="mealDetailsModalLabel{{ $meal->id }}">{{ $meal->name }} - Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <h5>Recipe Items:</h5>
                    @if($meal->ingredients->isNotEmpty())
                        <ul>
                            @foreach($meal->ingredients as $ingredient)
                                <li>{{ $ingredient->name }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{-- <p>No ingredients listed.</p> --}}
                    @endif

                    <h5 class="mt-3">Plan Feature:</h5>
                    @if($meal->remarks->isNotEmpty())
                        <ul>
                            @foreach($meal->remarks as $remark)
                                <li>{{ $remark->name }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{-- <p>No remarks.</p> --}}
                    @endif
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('meal.purchase', encrypt($meal->id)) }}" class="btn btn-zopa">Buy Now</a>
                  </div>
                </div>
              </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
