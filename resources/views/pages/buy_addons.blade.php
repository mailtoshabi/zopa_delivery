@extends('layouts.app')

@section('title', 'Buy Addons - Zopa Food Drop')

@section('content')
<style>
    .addon-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 8px;
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

    .addon-top-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .addon-bottom-row {
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
        margin-left: 10px;
    }

    .addon-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
    }

    .addon-details {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        flex-grow: 1;
    }

    .addon-name {
        font-weight: normal;
        cursor: pointer;
        font-size: 14px;
    }

    .qty-box {
        width: 80px;
    }

    @media (max-width: 576px) {
        .addon-image {
            width: 50px;
            height: 50px;
        }

        .qty-box {
            width: 70px;
        }

        .addon-details {
            flex-wrap: nowrap;
            gap: 5px;
        }
    }
    </style>


<div class="container my-2">
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            Buy Addons
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('addons.purchase.confirm') }}" method="POST">
        @csrf
        <div class="row">
            @foreach($addons as $addon)
            <div class="col-md-6">
                <div class="addon-card" data-addon-id="{{ $addon->id }}">
                    <div class="addon-top-row">
                        @if($addon->image_filename)
                        <img src="{{ Storage::url('addons/' . $addon->image_filename) }}" class="addon-image" alt="{{ $addon->name }}">
                        @endif
                        <label for="addon-{{ $addon->id }}" class="addon-name mb-0">
                            <div class="addon-details">
                                <input type="checkbox" class="form-check-input addon-checkbox" name="addons[{{ $addon->id }}][selected]" id="addon-{{ $addon->id }}" value="{{ $addon->id }}">
                                    {{ $addon->name }}
                                <span class="text-muted">₹{{ number_format($addon->price, 2) }}</span>

                                @if($addon->description)
                                    <i class="fa-solid fa-circle-info text-zopa"
                                    data-bs-toggle="tooltip"
                                    data-bs-html="true"
                                    data-bs-placement="top"
                                    title="{!! nl2br(e($addon->description)) !!}"></i>
                                @endif
                            </div>
                        </label>
                    </div>

                    <div class="addon-bottom-row">
                        <input type="number"
                               name="addons[{{ $addon->id }}][quantity]"
                               class="form-control qty-box addon-qty"
                               placeholder="Qty"
                               min="1"
                               value=""
                               disabled>
                    </div>
                </div>
                {{-- <div class="addon-card" data-addon-id="{{ $addon->id }}">
                    <div class="addon-info">
                        <div class="form-check">
                            <input type="checkbox" class="addon-checkbox d-none" name="addons[{{ $addon->id }}][selected]" id="addon-{{ $addon->id }}" value="{{ $addon->id }}">
                            @if($addon->image_filename)
                            <img src="{{ Storage::url('addons/' . $addon->image_filename) }}" class="addon-image" alt="{{ $addon->name }}">
                            @endif
                            <div>
                                <label class="fw-bold">{{ $addon->name }}</label>
                                <span class="text-muted ms-2">₹{{ number_format($addon->price, 2) }}</span>

                                @if($addon->description)
                                    <i class="fa-solid fa-circle-info text-zopa ms-2"
                                    data-bs-toggle="tooltip"
                                    data-bs-html="true"
                                    data-bs-placement="top"
                                    title="{!! nl2br(e($addon->description)) !!}"></i>
                                @endif
                            </div>
                        </div>

                        <div class="qty-wrapper">
                            <input type="number" name="addons[{{ $addon->id }}][quantity]" class="form-control qty-box addon-qty" placeholder="Qty" min="1" disabled>
                        </div>
                    </div>
                </div> --}}
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            {{-- <button type="submit" name="submit_action" value="add_to_cart" class="btn btn-outline-zopa px-5 me-2 mb-4">
                <i class="fa fa-cart-plus me-1"></i> Add to Cart
            </button> --}}

            <button type="submit" name="submit_action" value="checkout" class="btn btn-zopa px-5 mb-4">
                Continue to Checkout
            </button>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('.addon-card').forEach(card => {
        const qtyInput = card.querySelector('.addon-qty');
        const checkbox = card.querySelector('.addon-checkbox');

        // Function to enable qty box and focus
        const enableQty = () => {
            if (qtyInput.disabled) {
                qtyInput.disabled = false;
                qtyInput.focus();
            }
        };

        // Enable qty box when clicking on card (anywhere)
        card.addEventListener('click', function() {
            enableQty();
        });

        // Enable qty box when directly clicking inside qty box (if still disabled)
        qtyInput.addEventListener('click', function(e) {
            enableQty();
            e.stopPropagation();  // Prevents card click event doubling
        });

        // Auto toggle selection + disable qty input when empty
        qtyInput.addEventListener('input', function() {
            const qty = parseInt(this.value);

            if (qty > 0) {
                checkbox.checked = true;
                card.classList.add('selected');
            } else {
                checkbox.checked = false;
                card.classList.remove('selected');
                qtyInput.disabled = true;
            }
        });
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
