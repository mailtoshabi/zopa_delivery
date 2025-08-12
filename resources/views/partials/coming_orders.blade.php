<div class="row">
    @forelse($comingOrders as $order)
    <div class="col-sm-12 mb-3">
        <div class="card shadow {{ $order->status == 0 ? 'border-danger' : '' }}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <p><small>{{ $order->date->format('d M Y') }}</small></p>
                    <p class="mb-0 fw-bold">
                        {{ $order->quantity }} Meal{{ $order->quantity > 1 ? 's' : '' }}
                        @if ($order->status == 0)
                            <span class="badge bg-danger ms-2">Cancelled</span>
                        @endif
                    </p>

                    @if($order->dailyAddons->isNotEmpty())
                        <ul class="mb-0 ps-3 small text-muted">
                            @foreach($order->dailyAddons as $addonItem)
                                <li>{{ $addonItem->addon->name }} ({{ $addonItem->quantity }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                @if ($order->status == 1 && now()->format('H:i') < $lastOrderTime)
                    <form method="POST" action="{{ route('customer.daily_meals.cancel', $order->id) }}" class="cancel-order">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Cancel before {{ $lastOrderTime }}">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </form>
                @elseif($order->status == 1)
                    <span class="text-muted" data-bs-toggle="tooltip" title="Cancellation time passed">
                        <i class="fa-solid fa-clock"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>
    @empty
        <div class="col-sm-12">
            <div class="alert alert-info">No upcoming orders scheduled.</div>
        </div>
    @endforelse
</div>
