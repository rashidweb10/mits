<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function showAppLoginForm()
    {
        return view('frontend.auth.app-login');
    }    

    /**
     * Handle login request
     * Supports: email/password, phone/password, Gmail OAuth
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $login = $request->input('login');
        $password = $request->input('password');

        // Determine if login is email or phone (10 digits)
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);
        $isPhone = preg_match('/^\d{10}$/', $login);

        if (!$isEmail && !$isPhone) {
            return back()->withErrors(['login' => 'Please enter a valid email or 10-digit phone number.'])->withInput();
        }

        // Find user by email or phone
        $user = null;
        if ($isEmail) {
            $user = User::where('email', $login)->first();
        } else {
            $user = User::where('phone', $login)->first();
        }

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withErrors(['login' => 'Invalid credentials.'])->withInput();
        }

        if (!$user->is_active) {
            return back()->withErrors(['login' => 'Your account is inactive.'])->withInput();
        }

        Auth::login($user, $request->has('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('auth.dashboard'))->with('success', 'Welcome back!');
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('frontend.auth.register');
    }

    /**
     * Handle registration request with OTP
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|regex:/^\d{10}$/|unique:users,phone',
            'location' => 'required|string|max:255',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // Must contain at least one lowercase letter
                'regex:/[A-Z]/',      // Must contain at least one uppercase letter
                'regex:/[0-9]/',      // Must contain at least one digit
                'regex:/[@$!%*#?&]/', // Must contain at least one special character
            ],
        ], [
            'phone.regex' => 'Phone number must be exactly 10 digits.',
            'phone.unique' => 'This phone number is already registered.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*#?&).',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Generate 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpExpiresAt = now()->addMinutes(10);

        // Create user with unverified status
        $user = User::create([
            'role_id' => 3,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'location' => $request->location,
            'password' => Hash::make($request->password),
            'email_otp' => $otp,
            'email_otp_expires_at' => $otpExpiresAt,
            'is_active' => 0, // Inactive until email verified
        ]);

        // Send OTP email
        try {
            Mail::to($user->email)->send(new OtpVerificationMail($otp));
        } catch (\Exception $e) {
            // Log error but continue
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
        }

        // Send notification to admin
        try {
            $adminEmail = config('custom.from_email');
            Mail::to($adminEmail)->send(new \App\Mail\NewUserRegistrationMail($user, 'Registration Form'));
        } catch (\Exception $e) {
            \Log::error('Failed to send admin notification email: ' . $e->getMessage());
        }

        // Store user ID in session for OTP verification
        session(['pending_verification_user_id' => $user->id]);

        return redirect()->route('auth.verify-otp')->with('success', 'Registration successful! Please verify your email with the OTP sent to your inbox.');
    }

    /**
     * Show OTP verification form
     */
    public function showVerifyOtpForm()
    {
        if (!session('pending_verification_user_id')) {
            return redirect()->route('auth.register')->with('error', 'Please register first.');
        }

        return view('frontend.auth.verify-otp');
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $userId = session('pending_verification_user_id');
        if (!$userId) {
            return redirect()->route('auth.register')->with('error', 'Session expired. Please register again.');
        }

        $user = User::find($userId);
        if (!$user) {
            session()->forget('pending_verification_user_id');
            return redirect()->route('auth.register')->with('error', 'User not found. Please register again.');
        }

        // Check if OTP matches and is not expired
        if ($user->email_otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP.'])->withInput();
        }

        if (now()->gt($user->email_otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.'])->withInput();
        }

        // Verify user
        $user->email_verified_at = now();
        $user->email_otp = null;
        $user->email_otp_expires_at = null;
        $user->is_active = 1;
        $user->save();

        // Clear session
        session()->forget('pending_verification_user_id');

        // Auto login
        Auth::login($user);

        return redirect()->route('auth.dashboard')->with('success', 'Email verified successfully! Welcome to your dashboard!');
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $userId = session('pending_verification_user_id');
        if (!$userId) {
            return redirect()->route('auth.register')->with('error', 'Session expired. Please register again.');
        }

        $user = User::find($userId);
        if (!$user) {
            session()->forget('pending_verification_user_id');
            return redirect()->route('auth.register')->with('error', 'User not found. Please register again.');
        }

        // Check if user is already verified
        if ($user->email_verified_at) {
            session()->forget('pending_verification_user_id');
            Auth::login($user);
            return redirect()->route('auth.dashboard')->with('success', 'Your email is already verified.');
        }

        // Generate new OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpExpiresAt = now()->addMinutes(10);

        $user->email_otp = $otp;
        $user->email_otp_expires_at = $otpExpiresAt;
        $user->save();

        // Send OTP email
        try {
            Mail::to($user->email)->send(new OtpVerificationMail($otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send OTP email. Please try again later.');
        }

        return redirect()->route('auth.verify-otp')->with('success', 'A new OTP has been sent to your email address. Please check your inbox.');
    }

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists by email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // If user is not active, redirect with an error
                if (!$user->is_active) {
                    return redirect()->route('auth.login')->with('error', 'Your account is inactive. Please contact support.');
                }
                
                // Update Google ID and email verification if not set
                $needsUpdate = false;
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                    $needsUpdate = true;
                }
                // Mark email as verified since it's from Google OAuth
                if (!$user->email_verified_at) {
                    $user->email_verified_at = now();
                    $needsUpdate = true;
                }
                if ($needsUpdate) {
                    $user->save();
                }

                // Login existing user
                Auth::login($user, true);
            } else {
                // Create new user
                $user = User::create([
                    'role_id' => 3,
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(32)), // Random password for OAuth users
                    'email_verified_at' => now(), // Gmail accounts are pre-verified
                    'is_active' => 1,
                ]);

                // Send notification to admin
                try {
                    $adminEmail = config('custom.from_email');
                    Mail::to($adminEmail)->send(new \App\Mail\NewUserRegistrationMail($user, 'Google'));
                } catch (\Exception $e) {
                    \Log::error('Failed to send admin notification email: ' . $e->getMessage());
                }

                Auth::login($user, true);
            }

            return redirect()->intended(route('auth.dashboard'))->with('success', 'Welcome! You have been logged in with Google.');
        } catch (\Exception $e) {
            \Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect()->route('auth.login')->with('error', 'Failed to authenticate with Google. Please try again.');
        }
    }

    /**
     * Show dashboard page
     */
    public function dashboard()
    {
        $user = Auth::user();
        return view('frontend.auth.dashboard', compact('user'));
    }

    /**
     * Show profile page
     */
    public function profile()
    {
        $user = Auth::user();
        return view('frontend.auth.profile', compact('user'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Build validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ];

        // If phone is empty, allow updating it with validation
        if (empty($user->phone)) {
            $rules['phone'] = 'required|string|regex:/^\d{10}$/|unique:users,phone';
        }

        $validator = Validator::make($request->all(), $rules, [
            'phone.regex' => 'Phone number must be exactly 10 digits.',
            'phone.unique' => 'This phone number is already registered.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update name and location
        $user->name = $request->name;
        $user->location = $request->location;

        // Update phone only if it was empty
        if (empty($user->phone) && $request->has('phone')) {
            $user->phone = $request->phone;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        $user = Auth::user();
        return view('frontend.auth.change-password', compact('user'));
    }

    /**
     * Update password
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // Must contain at least one lowercase letter
                'regex:/[A-Z]/',      // Must contain at least one uppercase letter
                'regex:/[0-9]/',      // Must contain at least one digit
                'regex:/[@$!%*#?&]/', // Must contain at least one special character
            ],
        ], [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*#?&).',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Show enrolled courses page
     */
    public function enrolledCourses(Request $request)
    {
        $user = Auth::user();
        
        // Get search parameters
        $search = $request->input('search');
        
        // Start building the query
        $query = \App\Models\CourseEnrolment::with('course.category')
            ->where('user_id', $user->id)
            ->where('is_active', 1);
        
        // Add search filter for course and category name
        if ($search) {
            $query->whereHas('course', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhereHas('category', function ($q) use ($search) {
                      $q->where('name', 'like', '%'.$search.'%');
                  });
            });
        }
        
        // Set default sorting by enrolment ID
        $query->orderBy('id', 'desc');
        
        // Implement Laravel pagination with 25 records per page
        $enrolledCourses = $query->paginate(10);
        
        return view('frontend.auth.enrolled-courses', compact('user', 'enrolledCourses'));
    }

    /**
     * Show a single enrolled course and its materials
     */
    public function enrolledCourseShow(\App\Models\Course $course)
    {
        // Check if this is an admin preview
        $isPreview = request()->has('preview');
        
        if ($isPreview) {
            // For preview mode, check if user is an admin
            if (!Auth::check() || Auth::user()->role_id != 1) {
                abort(403, 'Unauthorized access.');
            }
            $user = Auth::user();
        } else {
            // Normal mode: Check authentication and enrollment
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('auth.login');
            }

            // Ensure the user is enrolled in this course and the enrollment is active
            $isEnrolled = \App\Models\CourseEnrolment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('validity', '>', Carbon::now()->startOfDay())
                ->where('is_active', 1)
                ->exists();

            if (!$isEnrolled) {
                abort(403, 'You are not enrolled in this course.');
            }
        }

        // Load the course with its materials
        $course->load([
            'materials' => function ($q) {
                $q->orderBy('id', 'asc');
                //->orderBy('sorting_id', 'asc'); //
            }
        ]);

        return view('frontend.auth.enrolled-course-show', compact('user', 'course'));
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('frontend.auth.forgot-password');
    }

    /**
     * Handle forgot password request
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        // Don't reveal if email exists or not for security
        if (!$user) {
            return redirect()->route('auth.forgot-password')->with('success', 'If the email exists, a password reset OTP has been sent.');
        }

        // Generate 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpExpiresAt = now()->addMinutes(10);

        // Store OTP in user record (we can reuse email_otp field or create a separate field)
        // For now, we'll use a session-based approach
        $user->email_otp = $otp;
        $user->email_otp_expires_at = $otpExpiresAt;
        $user->save();

        // Send OTP email
        try {
            Mail::to($user->email)->send(new PasswordResetOtpMail($otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset OTP email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send password reset email. Please try again later.');
        }

        // Store user ID in session for OTP verification
        session(['password_reset_user_id' => $user->id]);

        return redirect()->route('auth.reset-password')->with('success', 'A password reset OTP has been sent to your email address.');
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm()
    {
        if (!session('password_reset_user_id')) {
            return redirect()->route('auth.forgot-password')->with('error', 'Please request a password reset first.');
        }

        return view('frontend.auth.reset-password');
    }

    /**
     * Handle reset password request
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|size:6',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // Must contain at least one lowercase letter
                'regex:/[A-Z]/',      // Must contain at least one uppercase letter
                'regex:/[0-9]/',      // Must contain at least one digit
                'regex:/[@$!%*#?&]/', // Must contain at least one special character
            ],
        ], [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*#?&).',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $userId = session('password_reset_user_id');
        if (!$userId) {
            return redirect()->route('auth.forgot-password')->with('error', 'Session expired. Please request a new password reset.');
        }

        $user = User::find($userId);
        if (!$user) {
            session()->forget('password_reset_user_id');
            return redirect()->route('auth.forgot-password')->with('error', 'User not found. Please request a new password reset.');
        }

        // Check if OTP matches and is not expired
        if ($user->email_otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP.'])->withInput();
        }

        if (now()->gt($user->email_otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new password reset.'])->withInput();
        }

        // Reset password
        $user->password = Hash::make($request->password);
        $user->email_otp = null;
        $user->email_otp_expires_at = null;
        $user->save();

        // Clear session
        session()->forget('password_reset_user_id');

        return redirect()->route('auth.login')->with('success', 'Your password has been reset successfully. Please login with your new password.');
    }

    /**
     * Resend password reset OTP
     */
    public function resendPasswordResetOtp()
    {
        $userId = session('password_reset_user_id');
        if (!$userId) {
            return redirect()->route('auth.forgot-password')->with('error', 'Session expired. Please request a password reset again.');
        }

        $user = User::find($userId);
        if (!$user) {
            session()->forget('password_reset_user_id');
            return redirect()->route('auth.forgot-password')->with('error', 'User not found.');
        }

        // Generate new OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpExpiresAt = now()->addMinutes(10);

        $user->email_otp = $otp;
        $user->email_otp_expires_at = $otpExpiresAt;
        $user->save();

        // Send OTP email
        try {
            Mail::to($user->email)->send(new PasswordResetOtpMail($otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset OTP email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send OTP. Please try again later.');
        }

        return redirect()->route('auth.reset-password')->with('success', 'A new password reset OTP has been sent to your email address.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
