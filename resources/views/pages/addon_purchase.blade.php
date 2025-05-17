@extends('layouts.app')

@section('title', 'Confirm Addon Purchase - Zopa Food Drop')

@section('content')
<style>
    .addon-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        box-shadow: 0 0 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        cursor: pointer;
        flex-wrap: wrap;
    }

    .addon-card:hover {
        background-color: #f8f9fa;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    .addon-card.selected {
        border-color: #0d6efd;
        background-color: #e8f0ff;
    }

    .addon-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .addon-info {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-check {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        width: 100%;
        padding-left: 0;
    }

    .form-check-input[type=checkbox] {
        display: none;
    }

    .qty-box {
        width: 80px;
        margin-left: auto;
    }

    #payment-message {
        padding: 10px 12px;
        border-radius: 6px;
        margin-top: 8px;
        font-size: 14px;
        line-height: 1.5;
    }

    #payment-message.info {
        background-color: #e7f3ff;
        border: 1px solid #b3daff;
        color: #084298;
    }

    #payment-message.warning {
        background-color: #fff4e5;
        border: 1px solid #ffc107;
        color: #664d03;
    }

    /* Mobile tweaks */
    @media (max-width: 576px) {
        .addon-card {
            flex-direction: column;
            align-items: flex-start;
        }

        .addon-image {
            margin-right: 0;
            margin-bottom: 5px;
            width: 18%;
            height: 18%;
        }

        .addon-info {
            font-size: 90%;
        }

        .form-check {
            flex-direction: column;
            align-items: flex-start;
        }

        .qty-box {
            /* width: 100%; */
            /* margin-left: 0; */
            margin-left: auto;
            /* margin-top: 10px; */
        }
    }
</style>

@php $grandTotal = 0; @endphp

<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">Confirm Addon Purchase</h2>
        <div class="mt-1" style="width: 160px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('addons.purchase.store') }}">
        @csrf

        <div class="row justify-content-center">
            <div class="col-md-10">
                @foreach($addons as $addon)
                    @php
                        $subtotal = $addon->price * $addon->selected_quantity;
                        $grandTotal += $subtotal;
                    @endphp

                    <div class="card shadow mb-3 addon-item" data-price="{{ $addon->price }}">
                        <div class="card-header align-items-center d-flex">
                            <h5 class="mb-1">{{ $addon->name }}
                                @if($addon->description)
                                    <small><i class="fa-solid fa-circle-info text-zopa"
                                    data-bs-toggle="tooltip"
                                    data-bs-html="true"
                                    data-bs-placement="top"
                                    title="{!! nl2br(e($addon->description)) !!}"></i></small>
                                @endif
                            </h5>
                        </div>
                        <div class="card-body d-flex align-items-center">
                            @if($addon->image_filename)
                                <img src="{{ Storage::url('addons/' . $addon->image_filename) }}" alt="{{ $addon->name }}" class="addon-image" style="object-fit: cover; margin-right: 15px;">
                            @endif
                            <div class="flex-grow-1">
                                <p class="text-muted mb-1">
                                    <i class="fa-solid fa-indian-rupee-sign"></i> {{ number_format($addon->price, 2) }} x
                                    <input type="number" name="addons[{{ $addon->id }}][quantity]" value="{{ $addon->selected_quantity }}" min="1" class="form-control d-inline-block qty-input" style="width: 70px; display: inline-block;">
                                </p>
                                <p class="mb-0"><strong>Subtotal:</strong> ₹<span class="addon-subtotal">{{ number_format($subtotal, 2) }}</span></p>
                            </div>
                        </div>
                    </div>
                @endforeach


                <div class="text-end mt-4">
                    <h5><strong>Grand Total: ₹<span id="grand-total">{{ number_format($grandTotal, 2) }}</span></strong></h5>
                </div>

                <div class="mb-3 mt-4">
                    <label for="pay_method" class="form-label">Select Payment Method</label>
                    <select name="pay_method" id="pay_method" class="form-select @error('pay_method') is-invalid @enderror">
                        <option value="">-- Choose Payment Option --</option>
                        <option value="{{ Utility::PAYMENT_ONLINE }}">Online Payment</option>
                        <option value="{{ Utility::PAYMENT_COD }}">Cash on Delivery</option>
                    </select>
                    @error('pay_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div id="payment-message" class="form-text mt-2 text-muted mb-2"></div>
                <button type="submit" class="btn btn-zopa w-100 mt-3">Confirm and Pay</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', function() {
            const card = this.closest('.addon-item');
            const price = parseFloat(card.dataset.price);
            let qty = parseInt(this.value);

            if (isNaN(qty) || qty < 1) {
                qty = 1;
                this.value = 1;
            }

            // Update Subtotal
            const subtotal = price * qty;
            card.querySelector('.addon-subtotal').textContent = subtotal.toFixed(2);

            // Recalculate Grand Total
            let grandTotal = 0;
            document.querySelectorAll('.addon-item').forEach(item => {
                const itemPrice = parseFloat(item.dataset.price);
                const itemQty = parseInt(item.querySelector('.qty-input').value);
                grandTotal += itemPrice * itemQty;
            });
            document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
        });
    });

    // Payment method message toggle
    const payMethodSelect = document.getElementById('pay_method');
    const paymentMessageDiv = document.getElementById('payment-message');

    const onlineMessage = "Your wallet will be credited instantly with addons after a successful online payment.";
    const codMessage = "Your wallet will be credited manually by an admin only after payment confirmation. Choose online payment for instant credit.";

    payMethodSelect.addEventListener('change', function() {
        const value = this.value;
        paymentMessageDiv.classList.remove('info'); // clear before applying

        if (value === '{{ Utility::PAYMENT_ONLINE }}') {
            paymentMessageDiv.textContent = onlineMessage;
            paymentMessageDiv.classList.add('info');
        } else if (value === '{{ Utility::PAYMENT_COD }}') {
            paymentMessageDiv.textContent = codMessage;
            paymentMessageDiv.classList.add('info');
        } else {
            paymentMessageDiv.textContent = '';
        }
    });
    </script>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('form').on('submit', function() {
                $(this).find('button[type=submit]').prop('disabled', true).text('Progress...');
            });
        });
    </script>
@endpush
