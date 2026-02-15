<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseCategory;
use App\Models\Company;

class CourseCategoryController extends Controller
{
    protected $moduleName;

    public function __construct()
    {
        //Module Name
        $this->moduleName = 'Course Categories';
        view()->share('moduleName', $this->moduleName);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the search parameter from the request
        $companyId = request()->input('company');
        $search = request()->input('search');
    
        // Start building the query
        $query = CourseCategory::query();
    
        // Filter by authenticated user's company_id if available
        if (auth()->user()->company_id) {
            $query->where('company_id', auth()->user()->company_id);
        }
    
        // Additional filtering by request input (optional)
        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if ($search) {
            $query->where(function($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            });
        }      

        $query->orderBy('id', 'desc');
    
        $pageData = $query->paginate(config('custom.pagination_per_page'));
    
        // Get dropdown data for companies
        $companyList = getCompanyList();
    
        // Return the view with data
        return view('backend.course-categories.index', compact('pageData', 'companyList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.course-categories.create');
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
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        // If validation passes, proceed to saving the data
        $CourseCategory = new CourseCategory();
        $CourseCategory->name = $request->input('name');
        $CourseCategory->image = $request->input('image');
        $CourseCategory->description = $request->input('description');
        $CourseCategory->save();

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
        $pageData = CourseCategory::findOrFail($id);
        return view('backend.course-categories.edit', compact('pageData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the existing record by ID
        $CourseCategory = CourseCategory::findOrFail($id);
    
        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:200',
            'image' => 'required',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        // If validation passes, update the data
        $CourseCategory->name = $request->input('name');
        $CourseCategory->image = $request->input('image');
        $CourseCategory->description = $request->input('description');
        $CourseCategory->is_active = $request->input('is_active');
        $CourseCategory->save();
    
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
            CourseCategory::destroy($id);
    
            // Redirect back with a success message
            return redirect()->route('course-categories.index')->with('success', 'Record deleted successfully!');
        } catch (\Exception $e) {
            // Log the error message and stack trace
            \Log::error('Error deleting CourseCategory record', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'team_category_id' => $id
            ]);
    
            // Redirect back with an error message
            return redirect()->route('course-categories.index')->with('error', 'There was an error deleting the record.');
        }
    }    
}