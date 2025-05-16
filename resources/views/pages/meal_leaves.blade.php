@extends('layouts.app')
@section('title', 'My Leaves - Zopa Food Drop')

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
            My Leaves
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
            <input type="date" id="leave_date" name="date" class="form-control"
                min="{{ now()->toDateString() }}"
                {{-- {{ $monthlyLeaveCount >= $maxLeaves ? 'disabled' : '' }} --}}
                required>
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
@endsection

@push('scripts')
<script>
    document.getElementById('leave-form').addEventListener('submit', function (e) {
        const dateInput = document.getElementById('leave_date');
        const selectedDate = new Date(dateInput.value);

        if (selectedDate.getDay() === 0) { // 0 = Sunday
            e.preventDefault();
            alert("Sundays are already off. You don't need to mark leave.");
        }
    });

    $(document).ready(function() {
        $('form').on('submit', function() {
            $(this).find('button[type=submit]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Marking...');
        });
    });
</script>
@endpush
