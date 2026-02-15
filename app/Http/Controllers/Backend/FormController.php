<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;

class FormController extends Controller
{

    protected $moduleName;
    protected $folderName;
    protected $routeName;

    public function __construct()
    {
        $this->moduleName = 'Forms';
        $this->folderName = 'forms';
        $this->routeName = 'forms';
        view()->share('moduleName', $this->moduleName);
        view()->share('folderName', $this->folderName);
        view()->share('routeName', $this->routeName);
    }

    public function index(Request $request, $form_name = null)
    {

        // Get the search parameter from the request
        $companyId = request()->input('company');
        $search = request()->input('search');

        $query = Form::query();
        if($form_name){
            $query->where('form_name', $form_name);
        }

        // Filter by authenticated user's company_id if available
        if (auth()->user()->company_id) {
            $query->where('company_id', auth()->user()->company_id);
        }
    
        // Additional filtering by request input (optional)
        if ($companyId) {
            $query->where('company_id', $companyId);
        }        

        if ($search) {
            $query->where('form_name', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('ip', 'like', '%' . $request->search . '%');
        }

        $pageData = $query->orderBy('created_at', 'desc')->paginate(config('custom.pagination_per_page'));

        //dd($pageData);

        $formNames = Form::select('form_name')->distinct()->pluck('form_name');

        // Get dropdown data for companies
        $companyList = getCompanyList();        

        return view('backend.' . $this->folderName . '.index', compact('pageData', 'companyList', 'formNames'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Attempt to delete the record
            Form::destroy($id);
    
            // Redirect back with a success message
            return redirect()->back()->with('success', 'Record deleted successfully!');
        } catch (\Exception $e) {
            // Log the error message and stack trace
            \Log::error('Error deleting Form record', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'form_id' => $id
            ]);
    
            // Redirect back with an error message
            return redirect()->back()->with('error', 'There was an error deleting the record.');
        }
    }

    /**
     * Bulk delete forms
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = explode(',', $request->input('ids'));
            
            if (empty($ids) || !is_array($ids)) {
                return response()->json(['status' => false, 'notification' => 'No items selected for deletion.']);
            }

            $deleted = Form::whereIn('id', $ids)->delete();
            
            if ($deleted > 0) {
                return response()->json([
                    'status' => true, 
                    'notification' => $deleted . ' record(s) deleted successfully!'
                ]);
            } else {
                return response()->json(['status' => false, 'notification' => 'No records were deleted.']);
            }
        } catch (\Exception $e) {
            \Log::error('Error bulk deleting Form records', [
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'ids' => $request->input('ids')
            ]);

            return response()->json(['status' => false, 'notification' => 'There was an error deleting the records.']);
        }
    }
}
