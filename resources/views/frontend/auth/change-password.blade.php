@extends('frontend.layouts.profile')

@section('meta.title', 'Change Password')
@section('meta.description', 'Change your password')

@php
    $pageTitle = 'Change Password';
@endphp

@section('profile-content')
<div class="bg-light p-4 p-md-5 rounded-3 shadow-sm">
    <h3 class="fw-bold mb-4 robot_slab">Change Password</h3>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('auth.change-password.store') }}" method="POST" onsubmit="protect_with_recaptcha_v3(this, 'change_password')">
        @csrf
        
        <div class="mb-3">
            <label for="current_password" class="form-label text-muted fw-medium">Current Password <span class="text-danger">*</span></label>
            <input 
                type="password" 
                class="form-control @error('current_password') is-invalid @enderror" 
                id="current_password" 
                name="current_password" 
                placeholder="Enter your current password"
                required 
            />
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-muted fw-medium">New Password <span class="text-danger">*</span></label>
            <input 
                type="password" 
                class="form-control @error('password') is-invalid @enderror" 
                id="password" 
                name="password" 
                placeholder="Enter new password"
                required 
            />
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">
                Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character (@$!%*#?&).
            </small>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label text-muted fw-medium">Confirm New Password <span class="text-danger">*</span></label>
            <input 
                type="password" 
                class="form-control" 
                id="password_confirmation" 
                name="password_confirmation" 
                placeholder="Confirm your new password"
                required 
            />
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                Change Password
            </button>
        </div>
    </form>
</div>
@endsection

