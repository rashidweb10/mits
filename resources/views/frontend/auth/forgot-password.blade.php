@extends('frontend.layouts.app')

@section('meta.title', 'Forgot Password')
@section('meta.description', 'Reset your password')

@section('content')

@include('frontend.partials.breadcrumb', ['title' => "Forgot Password"])

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="bg-light p-4 p-md-5 rounded-3 shadow-sm">
                    <h3 class="fw-bold mb-4 text-center robot_slab">Forgot Password</h3>

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

                    <p class="text-center text-muted mb-4">
                        Enter your email address and we'll send you an OTP to reset your password.
                    </p>

                    <form action="{{ route('auth.forgot-password') }}" method="POST" onsubmit="protect_with_recaptcha_v3(this, 'forgot_password')">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="email" class="form-label text-muted fw-medium">Email Address <span class="text-danger">*</span></label>
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="Enter your email address"
                                required 
                                autofocus
                            />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                                Send Reset OTP
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="mb-0">Remember your password? <a href="{{ route('auth.login') }}">Login here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

