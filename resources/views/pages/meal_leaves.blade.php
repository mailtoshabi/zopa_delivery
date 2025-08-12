@extends('layouts.app')
@section('title', 'My Leaves - ' . config('app.name'))

@section('content')
<div class="container my-2">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="text-center mb-4">
        <h2 class="position-relative d-inline-block px-4 py-2">
            {{ __('messages.page.my_leaves') }}
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>
    <div class="alert alert-info">
        You’ve used <strong>{{ $monthlyLeaveCount }}</strong> of <strong>{{ $maxLeaves }}</strong> monthly leaves.<br>
        You currently have <strong>{{ $activeLeaveCount }}</strong> of <strong>{{ $maxActiveLeaves }}</strong> active leaves.<br>
        Contact <a href="{{ route('support') }}">Support</a> for long-term leaves.
    </div>

    <form action="{{ route('customer.mark.leaves') }}" method="POST" id="leave-form">
        @csrf
        <div class="mb-3">
            <label for="leave_date" class="form-label">Select a Date to Mark Leave</label>
            <div class="input-group">
                <input type="text" id="leave_date" name="date" class="form-control" placeholder="dd-mm-yyyy" autocomplete="off" required>
                <button class="btn btn-outline-secondary" type="button" id="dateBtn">
                        <i class="fa fa-calendar"></i>
                    </button>
            </div>
        </div>
        <button type="submit" class="btn btn-zopa"
            {{-- {{ $monthlyLeaveCount >= $maxLeaves ? 'disabled' : '' }} --}}
            >
            Mark Leave
        </button>
    </form>

    <hr class="my-4">

    <h5 class="mb-3">My Leaves</h5>
    <ul class="list-group">
        @forelse($leaves as $leave)
            @php

                $leaveDate = \Carbon\Carbon::parse($leave->leave_at)->startOfDay();
                $today = \Carbon\Carbon::today();
                $now = now();

                $cutoff = Utility::getCutoffHourAndMinute();
                $cutoffHour = $cutoff['hour'];
                $cutoffMinute = $cutoff['minute'];

                $cutoffTime = \Carbon\Carbon::today()->setTime($cutoffHour, $cutoffMinute);

                $isExpired = false;

                if ($leaveDate->lt($today)) {
                    $isExpired = true;
                } elseif ($leaveDate->equalTo($today) && $now->gt($cutoffTime)) {
                    $isExpired = true;
                }

                if ($isExpired) {
                    $badge = '<span class="badge bg-secondary ms-2">Expired</span>';
                } else {
                    $badge = '<span class="badge bg-success ms-2">Active</span>';
                }
            @endphp
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <div class="d-flex align-items-center">
                    {{ $leaveDate->format('d M Y (l)') }}
                    {!! $badge !!}
                </div>
                @if (!$isExpired)
                    <form action="{{ route('customer.meal-leaves.destroy', $leave->id) }}" method="POST" onsubmit="return confirm('Cancel this leave?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                    </form>
                @endif
            </li>
        @empty
            <li class="list-group-item">No leaves marked yet.</li>
        @endforelse
    </ul>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3 z-3" style="z-index: 1055">
    <div id="leaveToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="leaveToastBody">
                Leave marked successfully.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@endsection

@push('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css">
    <style>
    .ui-datepicker {
        font-size: 14px;
        background-color: #f9f9f9;
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px;
        z-index: 1056 !important;
    }

    .ui-datepicker td a {
        padding: 6px;
        text-align: center;
        display: inline-block;
        background-color: #f8f9fa;
        border-radius: 4px;
        color: #333;
        transition: all 0.2s;
    }

    .ui-datepicker td a:hover {
        background-color: #f63b41;
        color: white;
    }

    .ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight {
        border: 1px solid #ec1d23;
        background: #ec1d23 50% top repeat-x;
        color: #ffffff !important;
    }

    .ui-datepicker .ui-datepicker-header {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 6px 0;
        font-weight: bold;
        border-radius: 6px 6px 0 0;
    }

    .ui-datepicker .ui-datepicker-prev,
    .ui-datepicker .ui-datepicker-next {
        cursor: pointer;
        color: white !important;
    }

    .ui-widget-header {
        border: 1px solid #e78f08;
        background: #ec1d23 50% 50% repeat-x !important;
    }

    .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, .ui-button, html .ui-button.ui-state-disabled:hover, html .ui-button.ui-state-disabled:active {
        color: #f67579;
    }

</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
$(function() {
    $("#leave_date").datepicker({
        dateFormat: 'dd-mm-yy', // format: day-month-year
        minDate: 0,             // disables past dates
        beforeShowDay: function(date) {
            const day = date.getDay();
            // 0 = Sunday
            return [day !== 0, "", day === 0 ? "Sundays not allowed" : ""];
        }
    });
});
</script>
<script>
$('#dateBtn').on('click', function() {
    $('#leave_date').datepicker('show');
});
</script>

<script>
$(document).on('submit', '#leave-form', function (e) {
    e.preventDefault();

    const form = $(this);
    const formData = form.serialize();
    const submitButton = form.find('button[type=submit]');
    const originalText = submitButton.html();

    submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

    $.ajax({
        url: form.attr('action'),
        method: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (response) {
            if (response.success) {
                // Show toast
                $('#leaveToastBody').text('Leave marked successfully.');
                const toast = new bootstrap.Toast(document.getElementById('leaveToast'));
                toast.show();

                // Reload after a short delay
                setTimeout(() => location.reload(), 1800);
            } else {
                alert(response.message || 'Something went wrong.');
                submitButton.prop('disabled', false).html(originalText);
            }
        },
        error: function (xhr) {
            let message = 'Validation failed.';
            if (xhr.responseJSON?.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.status === 422 && xhr.responseJSON?.errors) {
                const firstError = Object.values(xhr.responseJSON.errors)[0][0];
                message = firstError;
            }
            alert(message);
            submitButton.prop('disabled', false).html(originalText);
        }
    });
});
</script>
@endpush
