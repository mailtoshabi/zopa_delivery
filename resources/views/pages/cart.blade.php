@extends('layouts.app')
@section('title', 'My Cart')

@section('content')
<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            My Cart
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(empty($cart))
        <p>Your cart is empty.</p>
        <div class="mt-4">
            <p>Why not check out our <a href="{{ route('front.meal.plan') }}">Meal Plans</a> or <a href="{{ route('front.show.addons') }}">Addons</a>?</p>
        </div>
    @else
        <table class="table">
            <thead>
                <tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th><th>Action</th></tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($cart as $item)
                    @php
                        $subtotal = $item['price'] * $item['quantity'];
                        $grandTotal += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ ucfirst($item['type']) }} - {{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>₹{{ number_format($item['price'], 2) }}</td>
                        <td>₹{{ number_format($subtotal, 2) }}</td>
                        <td>
                            <form action="{{ route('cart.removeItem') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="{{ $item['type'] }}">
                                <input type="hidden" name="id" value="{{ $item['id'] }}">
                                <button class="btn btn-sm btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4>Grand Total: ₹{{ number_format($grandTotal, 2) }}</h4>

        <a href="{{ route('cart.checkout') }}" class="btn btn-success">Proceed to Checkout</a>
        <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-warning">Clear Cart</button>
        </form>
        <div class="mt-4">
            <p>Continue buying with <a href="{{ route('front.meal.plan') }}">Meal Plans</a> or <a href="{{ route('front.show.addons') }}">Addons</a>?</p>
        </div>
    @endif

</div>
@endsection
