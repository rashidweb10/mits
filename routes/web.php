<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

//Backend
use App\Http\Controllers\CommandController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\UploadController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\CourseCategoryController;
use App\Http\Controllers\Backend\CourseController;
use App\Http\Controllers\Backend\CourseMaterialController;
use App\Http\Controllers\Backend\CourseEnrolmentController;
use App\Http\Controllers\Backend\StudentController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\BlogCategoryController;
use App\Http\Controllers\Backend\TeamController;
use App\Http\Controllers\Backend\CampusController;
use App\Http\Controllers\Backend\GalleryController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\FormController as BackendFormController;
use App\Http\Controllers\Backend\ImportController;
use App\Http\Controllers\Backend\QuizController;
use App\Http\Controllers\Backend\QuizQuestionController;

//Frontend
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;
use App\Http\Controllers\FormController;

Route::get('/fetch-image', function () {

    $url = 'https://www.marinarch.in/uploads/22012020003330_about_inner.jpg';

    $path = fetchUploadFromUrl($url);

    if ($path === false) {
        return 'Image not found or invalid';
    }

    return asset($path);
});


Route::prefix('command')->group(function () {
    Route::get('cache-clear', [CommandController::class, 'cacheClear']);
    Route::get('config-clear', [CommandController::class, 'configClear']);
    Route::get('config-cache', [CommandController::class, 'configCache']);
    Route::get('route-cache', [CommandController::class, 'routeCache']);
    Route::get('route-clear', [CommandController::class, 'routeClear']);
    Route::get('view-clear', [CommandController::class, 'viewClear']);
    Route::get('view-cache', [CommandController::class, 'viewCache']);
    Route::get('storage-link', [CommandController::class, 'storageLink']);
    Route::get('key-generate', [CommandController::class, 'keyGenerate']);
    Route::get('optimize-clear', [CommandController::class, 'optimizeClear']);
    Route::get('queue-work', [CommandController::class, 'queueWork']);
    Route::get('queue-retry/{id?}', [CommandController::class, 'queueRetry']); // optional id
    Route::get('queue-failed', [CommandController::class, 'queueFailed']);
    Route::get('queue-forget/{id}', [CommandController::class, 'queueForget']);
    Route::get('queue-flush', [CommandController::class, 'queueFlush']);    
});

Route::get('/', [FrontendController::class, 'home'])->name('home');

Route::get('/about-us', [FrontendController::class, 'about'])->name('about');

Route::get('/products', [FrontendController::class, 'products'])->name('products');

Route::get('/contact-us', [FrontendController::class, 'contact'])->name('contact');
Route::get('/courses', [FrontendController::class, 'courses'])->name('courses');
Route::get('/faculties', [FrontendController::class, 'faculties'])->name('faculties');
Route::get('/testimonials', [FrontendController::class, 'testimonials'])->name('testimonials');

Route::get('/blog', [FrontendController::class, 'blogs'])->name('blog.index');
Route::get('/blog/{slug}', [FrontendController::class, 'blogDetail'])->name('blog.show');

Route::post('/submit-form', [FormController::class, 'submit'])->middleware(['protect.forms','recaptcha','throttle:10,1'])->name('form.submit');

// Frontend Authentication Routes
Route::prefix('auth')->group(function () {
    // Login
    Route::get('/login', [FrontendAuthController::class, 'showLoginForm'])->name('auth.login');
    Route::get('/app-login', [FrontendAuthController::class, 'showAppLoginForm'])->name('auth.app-login');
    Route::get('/login', [FrontendAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [FrontendAuthController::class, 'login'])->middleware(['protect.forms','recaptcha','throttle:10,1'])->name('auth.login');
    
    // Registration
    Route::get('/register', [FrontendAuthController::class, 'showRegisterForm'])->name('auth.register');
    Route::post('/register', [FrontendAuthController::class, 'register'])->middleware(['protect.forms','recaptcha','throttle:10,1'])->name('auth.register');
    
    // OTP Verification
    Route::get('/verify-otp', [FrontendAuthController::class, 'showVerifyOtpForm'])->name('auth.verify-otp');
    Route::post('/verify-otp', [FrontendAuthController::class, 'verifyOtp'])->middleware(['protect.forms','recaptcha','throttle:10,1'])->name('auth.verify-otp');
    Route::post('/resend-otp', [FrontendAuthController::class, 'resendOtp'])->middleware(['protect.forms','recaptcha','throttle:10,1'])->name('auth.resend-otp');
    
    // Forgot Password
    Route::get('/forgot-password', [FrontendAuthController::class, 'showForgotPasswordForm'])->name('auth.forgot-password');
    Route::post('/forgot-password', [FrontendAuthController::class, 'forgotPassword'])->middleware(['protect.forms','recaptcha','throttle:10,1'])->name('auth.forgot-password');
    
    // Reset Password
    Route::get('/reset-password', [FrontendAuthController::class, 'showResetPasswordForm'])->name('auth.reset-password');
    Route::post('/reset-password', [FrontendAuthController::class, 'resetPassword'])->middleware(['protect.forms','recaptcha','throttle:10,1'])->name('auth.reset-password');
    Route::post('/resend-password-reset-otp', [FrontendAuthController::class, 'resendPasswordResetOtp'])->middleware(['protect.forms','recaptcha','throttle:10,1'])->name('auth.resend-password-reset-otp');
    
    // Google OAuth
    Route::get('/google', [FrontendAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/google/callback', [FrontendAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    
    // Profile (Authenticated)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [FrontendAuthController::class, 'dashboard'])->name('auth.dashboard');
        Route::get('/profile', [FrontendAuthController::class, 'profile'])->name('auth.profile');
        Route::put('/profile', [FrontendAuthController::class, 'updateProfile'])->middleware(['protect.forms','recaptcha','throttle:10,1'])->name('auth.profile.update');
        Route::get('/change-password', [FrontendAuthController::class, 'showChangePasswordForm'])->name('auth.change-password');
        Route::post('/change-password', [FrontendAuthController::class, 'changePassword'])->middleware(['protect.forms','recaptcha','throttle:10,1'])->name('auth.change-password.store');
        Route::get('/enrolled-courses', [FrontendAuthController::class, 'enrolledCourses'])->name('auth.enrolled-courses');
         Route::get('/enrolled-courses/{course}', [FrontendAuthController::class, 'enrolledCourseShow'])->name('auth.enrolled-courses.show')->withoutMiddleware(['auth']);
        Route::post('/logout', [FrontendAuthController::class, 'logout'])->name('auth.logout');

        // Quiz Attempt Routes
        Route::get('/quiz-attempt/{quiz}', [\App\Http\Controllers\Frontend\QuizAttemptController::class, 'showQuizAttempt'])->name('auth.quiz-attempt');
        Route::post('/quiz-attempt/{quiz}', [\App\Http\Controllers\Frontend\QuizAttemptController::class, 'storeQuizAttempt'])->name('auth.quiz-attempt.store');
        Route::get('/quiz-result/{attempt}', [\App\Http\Controllers\Frontend\QuizAttemptController::class, 'showQuizResult'])->name('auth.quiz-result');
        Route::get('/certificate/{certificate}', [\App\Http\Controllers\Frontend\QuizAttemptController::class, 'downloadCertificate'])->name('auth.certificate.download');
    });
});

// Group routes under the 'backend' prefix
Route::prefix('backend')->group(function () {

    // Public login/logout routes
    Route::get('/', [AuthController::class, 'showLoginForm'])->middleware(['auth.guest', 'auth.backend.access'])->name('backend.login');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware(['auth.guest', 'auth.backend.access'])->name('backend.login');
    Route::post('/login', [AuthController::class, 'login'])->middleware(['recaptcha','throttle:10,60'])->name('backend.login.submit');
    Route::get('/logout', [AuthController::class, 'logout'])->name('backend.logout');

    // Authenticated admin routes
    Route::middleware(['auth.backend'])->group(function () {
        Route::get('/dashboard', function () {
            return view('backend.dashboard');
        })->name('backend.dashboard');
    });

    // Uploads routes 
    Route::middleware(['auth.backend'])->resource('/uploaded-files', UploadController::class);
    Route::middleware(['auth.backend'])->controller(UploadController::class)->group(function () {
        Route::any('/uploaded-files/file-info', 'file_info')->name('uploaded-files.info');
        Route::get('/uploaded-files/destroy/{id}', 'destroy')->name('uploaded-files.destroy');
        Route::post('/bulk-uploaded-files-delete', 'bulk_uploaded_files_delete')->name('bulk-uploaded-files-delete');
        Route::get('/all-file', 'all_file');
        Route::post('/aiz-uploader', 'show_uploader');
        Route::post('/aiz-uploader/upload', 'upload');
        Route::get('/aiz-uploader/get-uploaded-files', 'get_uploaded_files');
        Route::post('/aiz-uploader/get_file_by_ids', 'get_preview_files');
        Route::get('/aiz-uploader/download/{id}', 'attachment_download')->name('download_attachment');   
        Route::get('/aiz-uploader/generate-all-thumbnail', 'generate_all_thumbnails');     
    }); 
    
    // Schools routes
    Route::middleware(['auth.backend'])->group(function () {
        Route::get('/schools', function () {
            return view('backend.schools.index');
        })->name('backend.schools');
    });   
    
    Route::middleware('auth.backend')->group(function () {
        Route::resource('companies', CompanyController::class);
    });  

    Route::middleware('auth.backend')->group(function () {
        Route::resource('pages', PageController::class);
    });  

    Route::middleware('auth.backend')->group(function () {
        Route::resource('course-categories', CourseCategoryController::class);
    });  

    Route::middleware('auth.backend')->group(function () {
        Route::resource('courses', CourseController::class);
        Route::post('courses/bulk-delete', [CourseController::class, 'bulkDelete'])->name('courses.bulk-delete');
        Route::post('courses/bulk-active', [CourseController::class, 'bulkActive'])->name('courses.bulk-active');
        Route::post('courses/bulk-inactive', [CourseController::class, 'bulkInactive'])->name('courses.bulk-inactive');
    });  

    // AJAX route to get courses by category (used in Course Materials page filter)
    Route::middleware('auth.backend')->get('ajax/courses-by-category', [CourseController::class, 'getByCategory'])->name('courses.by-category');

    Route::middleware('auth.backend')->group(function () {
        Route::resource('course-materials', CourseMaterialController::class);
        Route::post('course-materials/bulk-delete', [CourseMaterialController::class, 'bulkDelete'])->name('course-materials.bulk-delete');
        Route::post('course-materials/bulk-active', [CourseMaterialController::class, 'bulkActive'])->name('course-materials.bulk-active');
        Route::post('course-materials/bulk-inactive', [CourseMaterialController::class, 'bulkInactive'])->name('course-materials.bulk-inactive');
    });  

     Route::middleware('auth.backend')->group(function () {
         Route::resource('course-enrolments', CourseEnrolmentController::class);
         Route::post('course-enrolments/bulk-delete', [CourseEnrolmentController::class, 'bulkDelete'])->name('course-enrolments.bulk-delete');
         Route::post('course-enrolments/bulk-active', [CourseEnrolmentController::class, 'bulkActive'])->name('course-enrolments.bulk-active');
         Route::post('course-enrolments/bulk-inactive', [CourseEnrolmentController::class, 'bulkInactive'])->name('course-enrolments.bulk-inactive');
         Route::get('course-enrolments/certificate/{certificate}', [CourseEnrolmentController::class, 'viewCertificate'])->name('course-enrolments.certificate');
         Route::get('course-enrolments/preview/{courseEnrolment}', [CourseEnrolmentController::class, 'previewCourseMaterials'])->name('course-enrolments.preview');
     }); 

    Route::middleware('auth.backend')->group(function () {
        Route::resource('students', StudentController::class);
        Route::post('students/bulk-delete', [StudentController::class, 'bulkDelete'])->name('students.bulk-delete');
        Route::post('students/bulk-active', [StudentController::class, 'bulkActive'])->name('students.bulk-active');
        Route::post('students/bulk-inactive', [StudentController::class, 'bulkInactive'])->name('students.bulk-inactive');
        Route::get('students/login-as/{id}', [StudentController::class, 'loginAsStudent'])->name('students.login-as');
    });

    Route::middleware('auth.backend')->group(function () {
        Route::resource('blog-categories', BlogCategoryController::class);
    });

    Route::middleware('auth.backend')->group(function () {
        Route::resource('blogs', BlogController::class);
        Route::post('blogs/bulk-delete', [BlogController::class, 'bulkDelete'])->name('blogs.bulk-delete');
        Route::post('blogs/bulk-active', [BlogController::class, 'bulkActive'])->name('blogs.bulk-active');
        Route::post('blogs/bulk-inactive', [BlogController::class, 'bulkInactive'])->name('blogs.bulk-inactive');
    });

    Route::middleware('auth.backend')->group(function () {
        Route::resource('quizzes', QuizController::class);
        Route::prefix('quizzes')->group(function () {
            Route::resource('{quiz}/questions', QuizQuestionController::class, ['as' => 'quizzes']);
        });
    });
    
    Route::middleware('auth.backend')->group(function () {
        Route::get('forms-by/{form_name}', [BackendFormController::class, 'index'])->name('forms.by');
        Route::get('forms-by/{form_name}/destroy/{id}', [BackendFormController::class, 'destroy'])->name('forms.destroy');
        Route::post('forms-by/bulk-delete', [BackendFormController::class, 'bulkDelete'])->name('forms.bulk-delete');
    });

    Route::middleware('auth.backend')->group(function () {
        Route::get('/import-course-categories', [ImportController::class, 'importCourseCategories']);
        Route::get('/import-courses', [ImportController::class, 'importCourses']);
        Route::get('/import-course-enrolments', [ImportController::class, 'importCourseEnrolments']);
        Route::get('/import-course-materials', [ImportController::class, 'importCourseMaterials']);
        Route::get('/import-users', [ImportController::class, 'importUsers']);
        Route::get('/import-course-materials-images', [ImportController::class, 'importCourseMaterialImages']);
    });
  
});


//Page Routes
Route::get('{slug}', function ($slug) {
    //$page = DB::table('pages')->where('slug', $slug)->first();
    $page = DB::table('pages')->where('slug', $slug)->where('company_id', config('custom.school_id'))->first();

    if (!$page) {
        abort(404);
    }

    switch ($page->layout) {
        case 'circulars':
            return app(FrontendController::class)->circulars($page->slug);
        case 'achivements':
            return app(FrontendController::class)->achivements($page->slug);
        case 'newsletter':
            return app(FrontendController::class)->newsletter($page->slug);  
        case 'default':
            return app(FrontendController::class)->default($page->slug);           
        default:
            abort(404, 'Route needs to be manually define.');
    }
})->where('slug', '.*');