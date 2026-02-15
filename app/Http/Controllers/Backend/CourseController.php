<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseCategory;

class CourseController extends Controller
{
    protected $moduleName;

    public function __construct()
    {
        //Module Name
        $this->moduleName = 'Courses';
        view()->share('moduleName', $this->moduleName);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the search parameter from the request
        $categoryId = request()->input('category');
        $search     = request()->input('search');
        $status     = request()->input('status');
    
        // Start building the query
        $query = Course::with('category');
    
        // Filter by category if provided
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Filter by status if provided
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            });
        }      
    
        $query->orderBy('id', 'desc');
    
        $pageData = $query->paginate(config('custom.pagination_per_page'));
    
        // Get dropdown data for categories
        $categoryList = CourseCategory::where('is_active', 1)->orderBy('name', 'asc')->get();
    
        // Return the view with data
        return view('backend.courses.index', compact('pageData', 'categoryList'));
    }

    /**
     * Return list of active courses for a given category (AJAX helper).
     */
    public function getByCategory(Request $request)
    {
        $categoryId = $request->input('category_id');
        $userId = $request->input('user_id');

        $query = Course::where('is_active', 1);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $courses = $query->orderBy('name', 'asc')->get(['id', 'name']);

        // If user_id is provided, check for already enrolled courses
        if ($userId) {
            $enrolledCourses = \App\Models\CourseEnrolment::where('user_id', $userId)
                ->pluck('course_id')
                ->toArray();

            // Add enrolled status to each course
            $courses->each(function ($course) use ($enrolledCourses) {
                $course->is_enrolled = in_array($course->id, $enrolledCourses);
            });
        }

        return response()->json($courses);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoryList = CourseCategory::where('is_active', 1)->orderBy('name', 'asc')->get();
        return view('backend.courses.create', compact('categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:200',
            'image' => 'required',
            'brochure' => 'nullable|string',
            'category_id' => 'required|exists:course_categories,id',
            'is_active' => 'required|boolean',
        ]);

        // If validation passes, proceed to saving the data
        $course = new Course();
        $course->name = $request->input('name');
        $course->image = $request->input('image');
        $course->brochure = $request->input('brochure');
        $course->category_id = $request->input('category_id');
        $course->is_active = $request->input('is_active');
        $course->save();

        // Return JSON response for AJAX handling
        return response()->json(['status' => true, 'notification' => 'Record created successfully!']);
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
        $pageData = Course::findOrFail($id);
        $categoryList = CourseCategory::where('is_active', 1)->orWhereIn('id', $pageData->pluck('category_id'))->orderBy('name', 'asc')->get();
        return view('backend.courses.edit', compact('pageData', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the existing record by ID
        $course = Course::findOrFail($id);
    
        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:200',
            'image' => 'required',
            'brochure' => 'nullable|string',
            'category_id' => 'required|exists:course_categories,id',
            'is_active' => 'required|boolean',
        ]);

        // If validation passes, update the data
        $course->name = $request->input('name');
        $course->image = $request->input('image');
        $course->brochure = $request->input('brochure');
        $course->category_id = $request->input('category_id');
        $course->is_active = $request->input('is_active');
        $course->save();
    
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
            Course::destroy($id);
    
            // Redirect back with a success message
            return redirect()->route('courses.index')->with('success', 'Record deleted successfully!');
        } catch (\Exception $e) {
            // Log the error message and stack trace
            \Log::error('Error deleting Course record', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'course_id' => $id
            ]);
    
            // Redirect back with an error message
            return redirect()->route('courses.index')->with('error', 'There was an error deleting the record.');
        }
    }

    /**
     * Bulk delete courses
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = explode(',', $request->input('ids'));
            
            if (empty($ids) || !is_array($ids)) {
                return response()->json(['status' => false, 'notification' => 'No items selected for deletion.']);
            }

            $deleted = Course::whereIn('id', $ids)->delete();
            
            if ($deleted > 0) {
                return response()->json([
                    'status' => true, 
                    'notification' => $deleted . ' record(s) deleted successfully!'
                ]);
            } else {
                return response()->json(['status' => false, 'notification' => 'No records were deleted.']);
            }
        } catch (\Exception $e) {
            \Log::error('Error bulk deleting Course records', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'ids' => $request->input('ids')
            ]);

            return response()->json(['status' => false, 'notification' => 'There was an error deleting the records.']);
        }
    }

    /**
     * Bulk activate courses
     */
    public function bulkActive(Request $request)
    {
        try {
            $ids = explode(',', $request->input('ids'));
            
            if (empty($ids) || !is_array($ids)) {
                return response()->json(['status' => false, 'notification' => 'No items selected for activation.']);
            }

            $updated = Course::whereIn('id', $ids)->update(['is_active' => 1]);
            
            if ($updated > 0) {
                return response()->json([
                    'status' => true, 
                    'notification' => $updated . ' record(s) activated successfully!'
                ]);
            } else {
                return response()->json(['status' => false, 'notification' => 'No records were activated.']);
            }
        } catch (\Exception $e) {
            \Log::error('Error bulk activating Course records', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'ids' => $request->input('ids')
            ]);

            return response()->json(['status' => false, 'notification' => 'There was an error activating the records.']);
        }
    }

    /**
     * Bulk deactivate courses
     */
    public function bulkInactive(Request $request)
    {
        try {
            $ids = explode(',', $request->input('ids'));
            
            if (empty($ids) || !is_array($ids)) {
                return response()->json(['status' => false, 'notification' => 'No items selected for deactivation.']);
            }

            $updated = Course::whereIn('id', $ids)->update(['is_active' => 0]);
            
            if ($updated > 0) {
                return response()->json([
                    'status' => true, 
                    'notification' => $updated . ' record(s) deactivated successfully!'
                ]);
            } else {
                return response()->json(['status' => false, 'notification' => 'No records were deactivated.']);
            }
        } catch (\Exception $e) {
            \Log::error('Error bulk deactivating Course records', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'ids' => $request->input('ids')
            ]);

            return response()->json(['status' => false, 'notification' => 'There was an error deactivating the records.']);
        }
    }    
}
