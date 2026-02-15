@extends('frontend.layouts.app')

@section('meta.title', 'Register')
@section('meta.description', 'Create a new account')

@section('content')

@php
    // Get home page video for background
    $homePage = \App\Models\Page::with('meta')->where('is_active', 1)->where('slug', 'home')->first();
    $banner_video = $homePage ? $homePage->meta->where('meta_key', 'banner_images')->first()->meta_value ?? '' : '';
@endphp

<div class="login-video-background position-relative">
    <video width="100%" height="100%" class="login-video" loop="loop" autoplay="" playsinline="" muted=""
        src="{{ $banner_video ? uploaded_asset($banner_video) : '' }}" id="register-video-bg"></video>
    <div class="login-video-overlay"></div>
    
    <section class="position-relative" style="z-index: 10;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    <div class="bg-light p-4 p-md-5 rounded-3 shadow-sm">
                    <h3 class="fw-bold mb-4 text-center robot_slab">Create your account</h3>

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

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('auth.register') }}" method="POST" onsubmit="protect_with_recaptcha_v3(this, 'register')">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label text-muted fw-medium">Name <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}"
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
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    placeholder="Enter your email"
                                    required 
                                />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label text-muted fw-medium">Phone (10 digits) <span class="text-danger">*</span></label>
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
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label text-muted fw-medium">Location <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control @error('location') is-invalid @enderror" 
                                    id="location" 
                                    name="location" 
                                    value="{{ old('location') }}"
                                    placeholder="Enter your location"
                                    required 
                                />
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label text-muted fw-medium">Password <span class="text-danger">*</span></label>
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Enter password"
                                    required 
                                />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character (@$!%*#?&).
                                </small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label text-muted fw-medium">Confirm Password <span class="text-danger">*</span></label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    placeholder="Confirm your password"
                                    required 
                                />
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                                Register
                            </button>
                        </div>

                        <div class="text-center mb-3">
                            <p class="mb-0">OR</p>
                        </div>

                        <div class="d-grid mb-3">
                            <a href="{{ route('auth.google') }}" class="btn btn-danger w-100 py-2">
                                <i class="fab fa-google me-2"></i> Register with Google
                            </a>
                        </div>

                        <div class="text-center">
                            <p class="mb-0">Already have an account? <a href="{{ route('auth.login') }}">Login here</a></p>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

