<div class="card mb-2">
    <div class="card-body">
        {{-- <h6 class="card-title">Meal #{{ $order->id }}</h6> --}}
        <p class="mb-0 fw-bold">
            @if ($order->status == 0)
            <span class="badge bg-danger ms-2">Cancelled</span>
            @endif
        </p>
        <p class="card-text">Ordered on: {{ $order->date->format('d M Y') }}</p>
        <p class="card-text">Quantity: {{ $order->quantity }}</p>
        @if($order->dailyAddons->isNotEmpty())
            <ul class="mb-0 ps-3 small text-muted">
                @foreach($order->dailyAddons as $addonItem)
                    <li>{{ $addonItem->addon->name }} ({{ $addonItem->quantity }})</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
