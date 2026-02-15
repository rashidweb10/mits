@extends('frontend.layouts.app')

@section('meta.title', 'Login')
@section('meta.description', 'Login to your account')

@section('content')

@php
    // Get home page video for background
    $homePage = \App\Models\Page::with('meta')->where('is_active', 1)->where('slug', 'home')->first();
    $banner_video = $homePage ? $homePage->meta->where('meta_key', 'banner_images')->first()->meta_value ?? '' : '';
@endphp

<div class="login-video-background position-relative">
    <video width="100%" height="100%" class="login-video" loop="loop" autoplay="" playsinline="" muted=""
        src="{{ $banner_video ? uploaded_asset($banner_video) : '' }}" id="login-video-bg"></video>
    <div class="login-video-overlay"></div>
    
    <section class="position-relative" style="z-index: 10;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="bg-light p-4 p-md-5 rounded-3 shadow-sm">
                    <h3 class="fw-bold mb-4 text-center robot_slab">Login to your account</h3>

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

                    <form action="{{ route('auth.login') }}" method="POST" onsubmit="protect_with_recaptcha_v3(this, 'login')">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="login" class="form-label text-muted fw-medium">Email or Phone</label>
                            <input 
                                type="text" 
                                class="form-control @error('login') is-invalid @enderror" 
                                id="login" 
                                name="login" 
                                value="{{ old('login') }}"
                                placeholder="Enter email or 10-digit phone number"
                                required 
                            />
                            @error('login')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label text-muted fw-medium">Password</label>
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                placeholder="Enter your password"
                                required 
                            />
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                            <div>
                                <a href="{{ route('auth.forgot-password') }}" class="text-decoration-none">Forgot Password?</a>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                                Login
                            </button>
                        </div>

                        <div class="text-center mb-3">
                            <p class="mb-0">OR</p>
                        </div>

                        <div class="d-grid mb-3">
                            <a href="{{ route('auth.google') }}" class="btn btn-danger w-100 py-2">
                                <i class="fab fa-google me-2"></i> Login with Google
                            </a>
                        </div>

                        <div class="text-center">
                            <p class="mb-0">Don't have an account? <a href="{{ route('auth.register') }}">Register here</a></p>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

