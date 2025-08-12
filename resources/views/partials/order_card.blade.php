<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h5 class="mb-1">{{ $order->walletGroup->display_name }}
                    @if (!$order->is_auto && $type=="daily")
                        <small><span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i> Extra ordered</span></small>
                    @endif
                </h5>
                <p class="mb-1 text-muted small">
                    <i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($order->date)->format('d M Y (l)') }}
                </p>
                <p class="mb-1 text-muted small">
                    <i class="bi bi-list-ol"></i> Quantity: <strong>{{ $order->quantity }}</strong>
                </p>
                @if($order->dailyAddons->isNotEmpty())
                <h5 class="mb-1">Addons</h5>
                    <ul class="mb-0 ps-3 small text-muted">
                        @foreach($order->dailyAddons as $addonItem)
                            <li>{{ $addonItem->addon->name }} ({{ $addonItem->quantity }})</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="text-end mt-2 mt-sm-0">
                @if ($order->is_delivered)
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Delivered</span>
                @else
                    @if($order->status)
                        <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Scheduled</span>
                    @else
                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Cancelled</span>
                    @endif
                @endif

                {{-- @if (
                    $order->status &&
                    !$order->is_delivered &&
                    // \Carbon\Carbon::parse($order->date)->isToday() &&
                    now()->lessThan($cutoffTime)
                ) --}}
                @if(isset($meal_type) && ($meal_type=='extra_meal') && $order->status)
                    <form action="{{ route('customer.daily_meals.cancel', $order->id) }}" method="POST" class="cancel-order mt-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger cancel-btn" data-order-id="{{ $order->id }}">
                            <i class="bi bi-x-circle"></i> Cancel Order
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>
