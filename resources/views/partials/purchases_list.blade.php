@foreach($purchases as $order)
    <div class="col-sm-12 mb-3">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <p style="margin-bottom: .1rem;"><small>{{ $order->created_at->format('d M Y') }}</small></p>
                    <p class="fw-bold">Invoice No: {{ $order->invoice_no }}</p>
                    {{-- @php $grandTotal = 0; @endphp --}}
                    @if($order->meals->isNotEmpty())
                        <p class="mb-0 ">
                            @foreach ($order->meals as $meal)
                            @php
                                $subtotal = $meal->price;
                                // $grandTotal += $subtotal;
                            @endphp
                                {{ $meal->meal->name }}  -
                                <i class="inr-size fa-solid fa-indian-rupee-sign"></i> {{ number_format($meal->price,2) }}
                            @endforeach
                        </p>
                    @endif
                    @if($order->addons->isNotEmpty())
                        <p class="mb-0 ">
                            @foreach ($order->addons as $index => $addon)
                            @php
                                $subtotal = $addon->price * $addon->quantity;
                                // $grandTotal += $subtotal;
                            @endphp
                                {{ $addon->quantity }} {{ $addon->addon->name }} x
                                <i class="inr-size fa-solid fa-indian-rupee-sign"></i> {{ number_format($addon->price, 2) }}

                                @if($order->addons->count() > 1 && $index < $order->addons->count() - 1)
                                    <br>
                                @endif
                            @endforeach
                        </p>
                    @endif
                    <h5>Grand Total: ₹{{ number_format($order->amount, 2) }}</h5>
                    <p>
                        @if ($order->is_paid)
                        <span class="badge bg-success ms-2">Paid</span>
                        @else
                        <span class="badge bg-danger ms-2">Not Paid</span>
                        @endif
                        @if ($order->status)
                        <span class="badge bg-success ms-2">Added to wallet</span>
                        @else
                        <span class="badge bg-danger ms-2">Not Active</span>
                        @endif
                        @if ($order->pay_method==Utility::PAYMENT_ONLINE)
                        <span class="badge bg-primary ms-2">Online Payment</span>
                        @else
                        <span class="badge bg-secondary ms-2">Offline Payment</span>
                        @endif

                    </p>
                </div>
            </div>
        </div>
    </div>
@endforeach

@if($purchases->hasMorePages())
    <div class="text-center mt-3">
        <button class="btn btn-outline-primary" id="load-more" data-next-page="{{ $purchases->currentPage() + 1 }}">Load More</button>
    </div>
@endif
