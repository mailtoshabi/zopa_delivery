@extends('layouts.app')

@section('title', 'Complete Your Payment')

@section('content')
<div class="container my-4">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">Complete Payment</h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center">
                    <p><strong>Total Amount to Pay:</strong></p>
                    <h3 class="mb-4 text-primary">₹{{ number_format($grandTotal, 2) }}</h3>

                    <button id="rzp-button" class="btn btn-success btn-lg">Pay Now</button>

                    <p class="mt-3 text-muted">You will be redirected to Razorpay secure payment gateway.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Razorpay JS -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const options = {
        "key": "{{ $razorpayKey }}",
        "amount": "{{ $grandTotal * 100 }}", // in paise
        "currency": "INR",
        "name": "Zopa Food Drop",
        "description": "Meal & Addon Purchase",
        "image": "{{ asset('front/images/logo_red.png') }}",
        "order_id": "{{ $order->id }}", // Razorpay order ID from backend
        "handler": function (response) {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "{{ route('meal.payment.verify') }}";

            const csrf = document.createElement("input");
            csrf.name = "_token";
            csrf.value = "{{ csrf_token() }}";
            form.appendChild(csrf);

            const paymentId = document.createElement("input");
            paymentId.name = "razorpay_payment_id";
            paymentId.value = response.razorpay_payment_id;
            form.appendChild(paymentId);

            const orderId = document.createElement("input");
            orderId.name = "razorpay_order_id";
            orderId.value = response.razorpay_order_id;
            form.appendChild(orderId);

            const signature = document.createElement("input");
            signature.name = "razorpay_signature";
            signature.value = response.razorpay_signature;
            form.appendChild(signature);

            document.body.appendChild(form);
            form.submit();
        },
        "prefill": {
            "name": "{{ $customer->name }}",
            "email": "{{ $customer->email ?? 'support@zopa.com' }}",
            "contact": "{{ $customer->phone }}"
        },
        "theme": {
            "color": "#0d6efd"
        }
    };

    const rzp = new Razorpay(options);
    document.getElementById('rzp-button').onclick = function (e) {
        e.preventDefault();

        const button = this;
        button.disabled = true;
        button.innerText = 'Processing...'; // Optional: show feedback

        rzp.open();

        // Re-enable the button if payment popup is closed by the user
        rzp.on('payment.failed', function () {
            button.disabled = false;
            button.innerText = 'Pay Now';
        });
    };
</script>
@endsection
