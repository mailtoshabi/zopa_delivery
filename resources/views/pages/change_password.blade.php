@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="container my-4">
    <h2 class="mb-4">Change Password</h2>

    <form method="POST" action="{{ route('customer.password.update') }}">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-body row g-3">

                <div class="col-md-6">
                    <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                    <input id="current_password" name="current_password" type="password" class="form-control">
                    @error('current_password') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                    <input id="new_password" name="new_password" type="password" class="form-control">
                    @error('new_password') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                    <input id="new_password_confirmation" name="new_password_confirmation" type="password" class="form-control">
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-zopa">
                <i class="fas fa-key me-1"></i> Change Password
            </button>
            <a href="{{ route('customer.profile') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Profile
            </a>
        </div>
    </form>
</div>
@endsection
