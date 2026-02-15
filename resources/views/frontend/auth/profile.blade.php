@extends('frontend.layouts.profile')

@section('meta.title', 'My Profile')
@section('meta.description', 'View and edit your profile')

@php
    $pageTitle = 'My Profile';
@endphp

@section('profile-content')
<div class="bg-light p-4 p-md-5 rounded-3 shadow-sm">
    <h3 class="fw-bold mb-4 robot_slab">Edit Profile</h3>

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

    <form action="{{ route('auth.profile.update') }}" method="POST" onsubmit="protect_with_recaptcha_v3(this, 'update_profile')">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label text-muted fw-medium">Name <span class="text-danger">*</span></label>
                <input 
                    type="text" 
                    class="form-control @error('name') is-invalid @enderror" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $user->name) }}"
                    placeholder="Enter your full name"
                    required 
                />
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label text-muted fw-medium">Email <span class="text-danger">*</span></label>
                <input 
                    type="email" 
                    class="form-control bg-light" 
                    id="email" 
                    value="{{ $user->email }}"
                    readonly
                    style="cursor: not-allowed;"
                />
                <small class="text-muted">Email cannot be changed</small>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label text-muted fw-medium">Phone (10 digits) 
                    @if(empty($user->phone))
                        <span class="text-danger">*</span>
                    @endif
                </label>
                @if(empty($user->phone))
                    <input 
                        type="tel" 
                        class="form-control @error('phone') is-invalid @enderror" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}"
                        placeholder="Enter 10-digit phone number"
                        pattern="[0-9]{10}"
                        maxlength="10"
                        inputmode="numeric"
                        required 
                    />
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Please enter your phone number</small>
                @else
                    <input 
                        type="tel" 
                        class="form-control bg-light" 
                        id="phone" 
                        value="{{ $user->phone }}"
                        readonly
                        style="cursor: not-allowed;"
                    />
                    <small class="text-muted">Phone cannot be changed</small>
                @endif
            </div>

            <div class="col-md-6 mb-3">
                <label for="location" class="form-label text-muted fw-medium">Location <span class="text-danger">*</span></label>
                <input 
                    type="text" 
                    class="form-control @error('location') is-invalid @enderror" 
                    id="location" 
                    name="location" 
                    value="{{ old('location', $user->location) }}"
                    placeholder="Enter your location"
                    required 
                />
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        @if($user->google_id)
        <div class="mb-3">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fab fa-google me-2"></i> This account is linked with Google.
            </div>
        </div>
        @endif

        <div class="mb-3">
            <small class="text-muted">
                <strong>Account Status:</strong> 
                @if($user->is_active)
                    <span class="text-success">Active</span>
                @else
                    <span class="text-danger">Inactive</span>
                @endif
                @if($user->email_verified_at)
                    | <span class="text-success">Email Verified</span>
                {{-- @else
                    | <span class="text-warning">Email Not Verified</span> --}}
                @endif
            </small>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                Update Profile
            </button>
        </div>
    </form>
</div>
@endsection
