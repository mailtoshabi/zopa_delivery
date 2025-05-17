@foreach ($previousOrders as $order)
    @include('partials._order_row', ['order' => $order])
@endforeach

@if ($previousOrders->hasMorePages())
    <div class="text-center mt-3">
        <button class="btn btn-outline-primary" id="load-more" data-next-page="{{ $previousOrders->currentPage() + 1 }}">
            Load More
        </button>
    </div>
@endif
