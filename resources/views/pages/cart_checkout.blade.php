@extends('layouts.app')

@section('title', 'Cart Checkout - Zopa Food Drop')

@section('content')
<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">Cart Checkout</h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    @if(empty($cart) || count($cart) === 0)
        <p class="text-center">Your cart is empty.</p>
    @else
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3">
                <div class="card shadow">
                    <div class="card-body">

                        <h5 class="mb-3">Items in Your Cart:</h5>
                        <ul class="list-group mb-3">
                            @php $grandTotal = 0; @endphp
                            @foreach($cart as $item)
                                @php
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $grandTotal += $subtotal;
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ ucfirst($item['type']) }} - {{ $item['name'] }}</strong><br>
                                        <small class="text-muted">Qty: {{ $item['quantity'] }} × ₹{{ number_format($item['price'], 2) }}</small>
                                    </div>
                                    <span class="fw-bold">₹{{ number_format($subtotal, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <h4 class="text-end mb-4">Grand Total: ₹{{ number_format($grandTotal, 2) }}</h4>

                        <form method="POST" action="{{ route('cart.checkout.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="pay_method" class="form-label">Select Payment Method</label>
                                <select name="pay_method" id="pay_method" class="form-select @error('pay_method') is-invalid @enderror">
                                    <option value="">-- Choose Payment Option --</option>
                                    <option value="{{ Utility::PAYMENT_ONLINE }}">Online Payment</option>
                                    <option value="{{ Utility::PAYMENT_COD }}">Cash on Delivery</option>
                                </select>
                                @error('pay_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn btn-zopa w-100">
                                Confirm and Pay
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
