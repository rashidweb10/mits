<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    protected $moduleName;

    public function __construct()
    {
        //Module Name
        $this->moduleName = 'Students';
        view()->share('moduleName', $this->moduleName);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the search/filter parameters from the request
        $search = request()->input('search');
        $status = request()->input('status');
    
        // Start building the query - only get users with role_id = 3 (students)
        $query = User::where('role_id', 3);
    
        // Filter by status if provided
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }

        // Free-text search on name, email, phone, location
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%');
            });
        }      
    
        $query->orderBy('id', 'desc');
    
        $pageData = $query->withCount('courseEnrolments')->paginate(config('custom.pagination_per_page'));
    
        // Return the view with data
        return view('backend.students.index', compact('pageData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:200',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:200',
            'password' => 'required|string|min:8',
            'is_active' => 'required|boolean',
        ]);

        // If validation passes, proceed to saving the data
        $student = new User();
        $student->role_id = 3; // Set role_id to 3 for students
        $student->company_id = $request->input('company_id');
        $student->name = $request->input('name');
        $student->email = $request->input('email');
        $student->phone = $request->input('phone');
        $student->location = $request->input('location');
        $student->password = Hash::make($request->input('password'));
        $student->is_active = $request->input('is_active');
        $student->save();

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
        $pageData = User::where('role_id', 3)->findOrFail($id);
        return view('backend.students.edit', compact('pageData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the existing record by ID and ensure it's a student (role_id = 3)
        $student = User::where('role_id', 3)->findOrFail($id);
    
        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:200',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:200',
            'password' => 'nullable|string|min:8',
            'is_active' => 'required|boolean',
        ]);
    
        // If validation passes, update the data
        $student->company_id = $request->input('company_id');
        $student->name = $request->input('name');
        $student->email = $request->input('email');
        $student->phone = $request->input('phone');
        $student->location = $request->input('location');
        
        // Only update password if provided
        if ($request->filled('password')) {
            $student->password = Hash::make($request->input('password'));
        }
        
        $student->is_active = $request->input('is_active');
        $student->save();
    
        // Return JSON response for AJAX handling
        return response()->json(['status' => true, 'notification' => 'Record updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Attempt to delete the record (only if it's a student)
            $student = User::where('role_id', 3)->findOrFail($id);
            $student->delete();
    
            // Redirect back with a success message
            return redirect()->route('students.index')->with('success', 'Record deleted successfully!');
        } catch (\Exception $e) {
            // Log the error message and stack trace
            \Log::error('Error deleting Student record', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'student_id' => $id
            ]);
    
            // Redirect back with an error message
            return redirect()->route('students.index')->with('error', 'There was an error deleting the record.');
        }
    }

    /**
     * Bulk delete students
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = explode(',', $request->input('ids'));
            
            if (empty($ids) || !is_array($ids)) {
                return response()->json(['status' => false, 'notification' => 'No items selected for deletion.']);
            }

            // Only delete students (role_id = 3)
            $deleted = User::where('role_id', 3)->whereIn('id', $ids)->delete();
            
            if ($deleted > 0) {
                return response()->json([
                    'status' => true, 
                    'notification' => $deleted . ' record(s) deleted successfully!'
                ]);
            } else {
                return response()->json(['status' => false, 'notification' => 'No records were deleted.']);
            }
        } catch (\Exception $e) {
            \Log::error('Error bulk deleting Student records', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'ids' => $request->input('ids')
            ]);

            return response()->json(['status' => false, 'notification' => 'There was an error deleting the records.']);
        }
    }

    /**
     * Bulk activate students
     */
    public function bulkActive(Request $request)
    {
        try {
            $ids = explode(',', $request->input('ids'));
            
            if (empty($ids) || !is_array($ids)) {
                return response()->json(['status' => false, 'notification' => 'No items selected for activation.']);
            }

            // Only update students (role_id = 3)
            $updated = User::where('role_id', 3)->whereIn('id', $ids)->update(['is_active' => 1]);
            
            if ($updated > 0) {
                return response()->json([
                    'status' => true, 
                    'notification' => $updated . ' record(s) activated successfully!'
                ]);
            } else {
                return response()->json(['status' => false, 'notification' => 'No records were activated.']);
            }
        } catch (\Exception $e) {
            \Log::error('Error bulk activating Student records', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'ids' => $request->input('ids')
            ]);

            return response()->json(['status' => false, 'notification' => 'There was an error activating the records.']);
        }
    }

    /**
     * Bulk deactivate students
     */
    public function bulkInactive(Request $request)
    {
        try {
            $ids = explode(',', $request->input('ids'));
            
            if (empty($ids) || !is_array($ids)) {
                return response()->json(['status' => false, 'notification' => 'No items selected for deactivation.']);
            }

            // Only update students (role_id = 3)
            $updated = User::where('role_id', 3)->whereIn('id', $ids)->update(['is_active' => 0]);
            
            if ($updated > 0) {
                return response()->json([
                    'status' => true,
                    'notification' => $updated . ' record(s) deactivated successfully!'
                ]);
            } else {
                return response()->json(['status' => false, 'notification' => 'No records were deactivated.']);
            }
        } catch (\Exception $e) {
            \Log::error('Error bulk deactivating Student records', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'ids' => $request->input('ids')
            ]);

            return response()->json(['status' => false, 'notification' => 'There was an error deactivating the records.']);
        }
    }

    /**
     * Login as a specific student
     */
    public function loginAsStudent($id)
    {
        try {
            // Find the student by ID and ensure it's a student (role_id = 3)
            $student = User::where('role_id', 3)->findOrFail($id);

            // Check if the current user is a superadmin (role_id = 1)
            if (auth()->user()->role_id != 1) {
                return response()->json(['status' => false, 'notification' => 'Unauthorized action.']);
            }

            // Log in as the student
            auth()->login($student);

            // Redirect to the student dashboard or any other appropriate page
            return response()->json(['status' => true, 'notification' => 'Logged in as student successfully!', 'redirect' => route('home')]);
        } catch (\Exception $e) {
            \Log::error('Error logging in as student', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'student_id' => $id
            ]);

            return response()->json(['status' => false, 'notification' => 'There was an error logging in as the student.']);
        }
    }
}

