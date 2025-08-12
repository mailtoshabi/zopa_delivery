@extends('kitchen..layouts.master')

@section('title') @lang('translation.Feedbacks') @endsection

@section('content')
@component('kitchen..dir_components.breadcrumb')
    @slot('li_1') @lang('translation.Customer_Manage') @endslot
    @slot('li_2') @lang('translation.Customer_Manage') @endslot
    @slot('title') @lang('translation.Feedbacks') @endslot
@endcomponent

@if(session()->has('success'))
<div class="alert alert-success alert-top-border alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-all me-3 align-middle text-success"></i><strong>Success</strong> - {{ session()->get('success') }}
</div>
@endif

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Feedback List <span class="text-muted fw-normal ms-2">({{ $feedbacks->total() }})</span></h4>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Message</th>
                        <th>Reply</th>
                        <th>Public?</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedbacks as $index => $feedback)
                    <tr>
                        <td>{{ $index + $feedbacks->firstItem() }}</td>
                        <td>{{ $feedback->customer->name }}<br><small>{{ $feedback->customer->phone }}</small></td>
                        <td>{{ $feedback->message }}</td>
                        <td>
                            @if($feedback->reply)
                                <strong class="text-success">Reply:</strong> {{ $feedback->reply }}<br>
                                <small class="text-muted">on {{ $feedback->replied_at?->format('d M Y h:i A') }}</small>
                            @endif

                            <form class="reply-form" data-id="{{ $feedback->id }}">
                                @csrf
                                <div class="mb-2">
                                    <textarea name="reply" class="form-control" rows="2" placeholder="Type reply...">{{ old('reply', $feedback->reply) }}</textarea>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" name="is_public" value="1" {{ $feedback->is_public ? 'checked' : '' }}>
                                    <label class="form-check-label">Publicly visible?</label>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">Save Reply</button>
                                <div class="reply-status text-success small mt-1" style="display: none;"></div>
                            </form>
                        </td>
                        <td>
                            <span class="badge bg-{{ $feedback->is_public ? 'success' : 'secondary' }}">
                                {{ $feedback->is_public ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>{{ $feedback->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination justify-content-center mt-3">
                {{ $feedbacks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.reply-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const feedbackId = this.dataset.id;
                const formData = new FormData(this);
                const csrfToken = formData.get('_token');

                fetch(`/kitchen./feedbacks/${feedbackId}/reply-ajax`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const statusDiv = this.querySelector('.reply-status');
                        statusDiv.textContent = 'Saved at ' + data.replied_at;
                        statusDiv.style.display = 'block';
                    }
                })
                .catch(err => {
                    alert('Error saving reply.');
                    console.error(err);
                });
            });
        });
    });
    </script>
    
@endsection
