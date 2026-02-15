@extends('frontend.layouts.app')

@section('meta.title', 'Verify Email')
@section('meta.description', 'Verify your email address')

@section('content')

@include('frontend.partials.breadcrumb', ['title' => "Verify Email"])

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="bg-light p-4 p-md-5 rounded-3 shadow-sm">
                    <h3 class="fw-bold mb-4 text-center robot_slab">Verify Your Email</h3>

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
                        We've sent a 6-digit OTP to your email address. Please enter it below to verify your account.
                    </p>

                    <form action="{{ route('auth.verify-otp') }}" method="POST" onsubmit="protect_with_recaptcha_v3(this, 'verify_otp')">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="otp" class="form-label text-muted fw-medium">Enter OTP</label>
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

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary w-100 py-2 fs-5">
                                Verify Email
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <p class="mb-2">Didn't receive the OTP?</p>
                        <form action="{{ route('auth.resend-otp') }}" method="POST" class="d-inline" onsubmit="protect_with_recaptcha_v3(this, 'resend_otp')">
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
    // Auto-focus and move to next input (if you want to split OTP into 6 inputs)
    document.getElementById('otp').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endsection

