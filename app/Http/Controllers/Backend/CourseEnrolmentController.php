<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseEnrolment;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use App\Models\Certificate;
use App\Models\QuizAttempt;
use Carbon\Carbon;

class CourseEnrolmentController extends Controller
{
    protected $moduleName;

    public function __construct()
    {
        //Module Name
        $this->moduleName = 'Course Enrolments';
        view()->share('moduleName', $this->moduleName);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the search/filter parameters from the request
        $categoryId = request()->input('category');
        $courseId   = request()->input('course');
        $search     = request()->input('search');
        $status     = request()->input('status');
        $validityFrom = request()->input('validity_from');
        $validityTo   = request()->input('validity_to');
    
        // Start building the query
        $query = CourseEnrolment::with('user', 'course.category');
    
        // Filter by category (via the related course) if provided
        if ($categoryId) {
            $query->whereHas('course', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        // Filter by course if provided
        if ($courseId) {
            $query->where('course_id', $courseId);
        }

        // Filter by status if provided
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        // Filter by validity date range
        if ($validityFrom) {
            $query->where('validity', '>=', $validityFrom);
        }
        if ($validityTo) {
            $query->where('validity', '<=', $validityTo);
        }

        // Free-text search on user name, email, and phone
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%')
                  ->orWhere('phone', 'like', '%'.$search.'%');
            });
        }      
    
        $query->orderBy('id', 'desc');
    
        $pageData = $query->paginate(config('custom.pagination_per_page'));
    
        // Load certificates manually for each enrolment
        foreach ($pageData as $enrolment) {
            $enrolment->certificate = Certificate::where('user_id', $enrolment->user_id)
                ->where('course_id', $enrolment->course_id)
                ->first();
        }
    
        // Get dropdown data for categories and courses
        $categoryList = CourseCategory::where('is_active', 1)->orderBy('name', 'asc')->get();

        $courseQuery = Course::where('is_active', 1);
        if ($categoryId) {
            $courseQuery->where('category_id', $categoryId);
        }
        $courseList = $courseQuery->orderBy('name', 'asc')->get();
    
        // Return the view with data
        return view('backend.course-enrolments.index', compact('pageData', 'courseList', 'categoryList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoryList = CourseCategory::where('is_active', 1)->orderBy('name', 'asc')->get();
        $courseList = Course::where('is_active', 1)->orderBy('name', 'asc')->get();
        // Get students (users with role_id = 3)
        $students = User::where('role_id', 3)->orderBy('name', 'asc')->get();
        return view('backend.course-enrolments.create', compact('courseList', 'categoryList', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'nullable|exists:course_categories,id',
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id',
            'validity' => 'nullable|date',
            'is_active' => 'required|boolean',
        ]);

        $courseIds = $request->input('course_ids', []);
        $userId = $request->input('user_id');
        $validity = $request->input('validity');
        $isActive = $request->input('is_active');

        $successCount = 0;
        $errorMessages = [];

        foreach ($courseIds as $courseId) {
            // Check if student is already enrolled in the same course
            $existingEnrolment = CourseEnrolment::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->first();

            if ($existingEnrolment) {
                $errorMessages[] = 'This student is already enrolled in the course: ' . Course::find($courseId)->name;
                continue;
            }

            // If validation passes, proceed to saving the data
            $courseEnrolment = new CourseEnrolment();
            $courseEnrolment->user_id = $userId;
            $courseEnrolment->course_id = $courseId;
            $courseEnrolment->validity = $validity;
            $courseEnrolment->is_active = $isActive;
            $courseEnrolment->save();

            $successCount++;
        }

        if (!empty($errorMessages)) {
            return response()->json([
                'status' => false,
                'notification' => implode('<br>', $errorMessages)
            ], 200);
        }

        // Return JSON response for AJAX handling
        return response()->json(['status' => true, 'notification' => $successCount . ' course(s) enrolled successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageData = CourseEnrolment::with('user', 'course')->findOrFail($id);
        $categoryList = CourseCategory::where('is_active', 1)->orderBy('name', 'asc')->get();
        
        // Get courses - include the current course even if inactive, and all active courses
        $courseList = Course::where('is_active', 1)
            ->orWhere('id', $pageData->course_id)
            ->orderBy('name', 'asc')
            ->get();
        
        // Get students (users with role_id = 3)
        $students = User::where('role_id', 3)->orderBy('name', 'asc')->get();
        
        return view('backend.course-enrolments.edit', compact('pageData', 'courseList', 'categoryList', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the existing record by ID
        $courseEnrolment = CourseEnrolment::findOrFail($id);
    
        // Validate the incoming data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'nullable|exists:course_categories,id',
            'course_id' => 'required|exists:courses,id',
            'validity' => 'nullable|date',
            'is_active' => 'required|boolean',
        ]);

        // Check if student is already enrolled in the same course (excluding current record)
        $existingEnrolment = CourseEnrolment::where('user_id', $request->input('user_id'))
            ->where('course_id', $request->input('course_id'))
            ->where('id', '!=', $id)
            ->first();

        if ($existingEnrolment) {
            return response()->json([
                'status' => false, 
                'notification' => 'This student is already enrolled in this course!'
            ], 200);
        }
    
        // If validation passes, update the data
        $courseEnrolment->user_id = $request->input('user_id');
        $courseEnrolment->course_id = $request->input('course_id');
        $courseEnrolment->validity = $request->input('validity');
        $courseEnrolment->is_active = $request->input('is_active');
        $courseEnrolment->save();
    
        // Return JSON response for AJAX handling
        return response()->json(['status' => true, 'notification' => 'Record updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Attempt to delete the record
            CourseEnrolment::destroy($id);
    
            // Redirect back with a success message
            return redirect()->route('course-enrolments.index')->with('success', 'Record deleted successfully!');
        } catch (\Exception $e) {
            // Log the error message and stack trace
            \Log::error('Error deleting CourseEnrolment record', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'course_enrolment_id' => $id
            ]);
    
            // Redirect back with an error message
            return redirect()->route('course-enrolments.index')->with('error', 'There was an error deleting the record.');
        }
    }

    /**
     * Bulk delete course enrolments
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = explode(',', $request->input('ids'));
            
            if (empty($ids) || !is_array($ids)) {
                return response()->json(['status' => false, 'notification' => 'No items selected for deletion.']);
            }

            $deleted = CourseEnrolment::whereIn('id', $ids)->delete();
            
            if ($deleted > 0) {
                return response()->json([
                    'status' => true, 
                    'notification' => $deleted . ' record(s) deleted successfully!'
                ]);
            } else {
                return response()->json(['status' => false, 'notification' => 'No records were deleted.']);
            }
        } catch (\Exception $e) {
            \Log::error('Error bulk deleting CourseEnrolment records', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'ids' => $request->input('ids')
            ]);

            return response()->json(['status' => false, 'notification' => 'There was an error deleting the records.']);
        }
    }

    /**
     * Bulk activate course enrolments
     */
    public function bulkActive(Request $request)
    {
        try {
            $ids = explode(',', $request->input('ids'));
            
            if (empty($ids) || !is_array($ids)) {
                return response()->json(['status' => false, 'notification' => 'No items selected for activation.']);
            }

            $updated = CourseEnrolment::whereIn('id', $ids)->update(['is_active' => 1]);
            
            if ($updated > 0) {
                return response()->json([
                    'status' => true, 
                    'notification' => $updated . ' record(s) activated successfully!'
                ]);
            } else {
                return response()->json(['status' => false, 'notification' => 'No records were activated.']);
            }
        } catch (\Exception $e) {
            \Log::error('Error bulk activating CourseEnrolment records', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'ids' => $request->input('ids')
            ]);

            return response()->json(['status' => false, 'notification' => 'There was an error activating the records.']);
        }
    }

    /**
     * Bulk deactivate course enrolments
     */
    public function bulkInactive(Request $request)
    {
        try {
            $ids = explode(',', $request->input('ids'));
            
            if (empty($ids) || !is_array($ids)) {
                return response()->json(['status' => false, 'notification' => 'No items selected for deactivation.']);
            }

            $updated = CourseEnrolment::whereIn('id', $ids)->update(['is_active' => 0]);
            
            if ($updated > 0) {
                return response()->json([
                    'status' => true, 
                    'notification' => $updated . ' record(s) deactivated successfully!'
                ]);
            } else {
                return response()->json(['status' => false, 'notification' => 'No records were deactivated.']);
            }
        } catch (\Exception $e) {
            \Log::error('Error bulk deactivating CourseEnrolment records', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'ids' => $request->input('ids')
            ]);

            return response()->json(['status' => false, 'notification' => 'There was an error deactivating the records.']);
        }
    }    

    /**
     * View certificate
     */
    public function viewCertificate(Certificate $certificate)
    {
        // Load certificate with related data
        $certificate->load(['user', 'course', 'quiz']);
        
        // Get the quiz attempt that earned this certificate
        $quizAttempt = QuizAttempt::where('user_id', $certificate->user_id)
            ->where('quiz_id', $certificate->quiz_id)
            ->where('is_passed', 1)
            ->first();
            
        return view('frontend.certificate.show', compact('certificate', 'quizAttempt'));
    }

    /**
     * Preview course materials as student (admin preview)
     */
    public function previewCourseMaterials(CourseEnrolment $courseEnrolment)
    {
        // Load the course with its materials
        $course = $courseEnrolment->course;
        $course->load([
            'materials' => function ($q) {
                $q->orderBy('id', 'desc');
            }
        ]);

        // Create a temporary user instance for preview
        $user = $courseEnrolment->user;

        // Generate the preview URL
        $previewUrl = route('auth.enrolled-courses.show', $course->id) . '?preview=true';

        return redirect()->to($previewUrl);
    }
}

