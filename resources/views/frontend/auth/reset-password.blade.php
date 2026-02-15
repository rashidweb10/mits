@extends('frontend.layouts.app')

@section('meta.title', 'Reset Password')
@section('meta.description', 'Reset your password')

@section('content')

@include('frontend.partials.breadcrumb', ['title' => "Reset Password"])

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="bg-light p-4 p-md-5 rounded-3 shadow-sm">
                    <h3 class="fw-bold mb-4 text-center robot_slab">Reset Password</h3>

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
                        Enter the OTP sent to your email and your new password.
                    </p>

                    <form action="{{ route('auth.reset-password') }}" method="POST" onsubmit="protect_with_recaptcha_v3(this, 'reset_password')">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="otp" class="form-label text-muted fw-medium">Enter OTP <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                class="form-control @error('otp') is-invalid @enderror text-center" 
                                id="otp" 
                                name="otp" 
                                placeholder="000000"
                                pattern="[0-9]{6}"
                                maxlength="6"
                                inputmode="numeric"
                                style="font-size: 24px; letter-spacing: 10px;"
                                required 
                                autofocus
                            />
                            @error('otp')
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

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                                Reset Password
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <p class="mb-2">Didn't receive the OTP?</p>
                        <form action="{{ route('auth.resend-password-reset-otp') }}" method="POST" class="d-inline" onsubmit="protect_with_recaptcha_v3(this, 'resend_password_reset_otp')">
                            @csrf
                            <button type="submit" class="btn btn-link p-0">Resend OTP</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    // Auto-format OTP input
    document.getElementById('otp').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endsection

