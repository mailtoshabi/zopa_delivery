@extends('layouts.app')

@section('title', 'Feedbacks - ' . config('app.name'))

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
            Feedbacks
        </h2>
        <div class="mt-1" style="width: 120px; height: 2px; background: #000000; margin: auto; border-radius: 2px;"></div>
    </div>
    <div class="row">
        <div class="col-sm-12 mb-3">
            <div class="card shadow">
                <div class="card-header">
                    <h5>Submit Your Feedback</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('customer.feedback.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="feedbackText" class="form-label">Your Feedback</label>
                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" id="feedbackText" rows="3" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-zopa">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($feedbacks->count())
    <div class="row mt-4">
        <div class="col-sm-12">
            <h5>Your Feedbacks</h5>
            @foreach($feedbacks as $feedback)
            <div class="card shadow mb-3">
                <div class="card-body">
                    <p class="mb-1">
                        <strong>{{ $feedback->customer->name ?? 'Customer' }}</strong>
                        <small class="text-muted float-end">{{ $feedback->created_at->diffForHumans() }}</small>
                    </p>
                    <p class="text-muted mb-1">{{ $feedback->message }}</p>

                    @if($feedback->reply)
                    <div class="border-start ps-3 mt-2">
                        <strong class="text-success">Admin Reply:</strong>
                        <p class="mb-0">{{ $feedback->reply }}</p>
                        <small class="text-muted">{{ $feedback->replied_at ? $feedback->replied_at->format('d M Y, h:i A') : '' }}</small>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-4">
                {{ $feedbacks->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
    <script>
        function showToast(message, type = 'success') {
            let toast = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('.flash-messages').prepend(toast);
        }
    </script>
@endpush
