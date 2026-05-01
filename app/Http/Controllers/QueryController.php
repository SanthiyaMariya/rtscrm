<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Query;
use App\Models\Employee;
use App\Models\ProjectMaster;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QueryController extends Controller
{
    // --- ADMIN FUNCTIONS ---

public function adminList()
{
    // 1. Fetch Queries from tbl_queries
    $queries = Query::orderBy('assigned_date', 'desc')->get();
    
    // 2. Fetch Employee Leaves from tbl_leave_requests
    $leaves = \DB::table('tbl_leave_requests')->orderBy('leave_date', 'desc')->get();
    
    // 3. Fetch Master Holidays from tbl_holiday_master
    $holidays = \DB::table('tbl_holiday_master')->orderBy('holiday_date', 'desc')->get();

    // 4. FETCH THE MISSING VARIABLE: Daily Reports from tbl_dailyreportspecial
    $dailyReports = \DB::table('tbl_dailyreportspecial')->orderBy('date', 'desc')->get();

    // 5. Return the view and send ALL four variables using compact
    return view('admin.queries.list', compact('queries', 'leaves', 'holidays', 'dailyReports'));
}
  
  public function create()
    {
        $projects = ProjectMaster::all();
        $employees = Employee::where('designation', '!=', 'Project Manager')->get();
        $products =\App\Models\Product::all();
         // ADD THIS LINE so the "Add" page can see the table data
    $queries = Query::orderBy('assigned_date', 'desc')->get(); 

        return view('admin.queries.add', compact('projects', 'employees','products'));
    }


 public function store(Request $request)
{
    $rows = $request->query_details ?? [];

    foreach ($rows as $key => $detail) {
        if (!empty($request->projectname[$key]) || !empty($request->productname[$key]) || !empty($request->common_project) || !empty($request->common_product)) {
            
            $projectName = $request->common_project ?? ($request->projectname[$key] ?? null);
            $productName = $request->common_product ?? ($request->productname[$key] ?? null);
            $title = $projectName ?? ($productName ?? 'General Task');
            $finalEmployee = !empty($request->assigned_to[$key]) ? $request->assigned_to[$key] : 'Not Assigned';
            $assignDate = !empty($request->assigned_date[$key]) ? $request->assigned_date[$key] : date('Y-m-d');
            $targetDate = !empty($request->target_date[$key]) ? $request->target_date[$key] : null;

            // 1. SAVE TO tbl_queries
            \DB::table('tbl_queries')->insert([
                'projectname'    => $projectName,
                'productname'    => $productName,
                'assigned_to'    => $finalEmployee,
                'emp_name'       => auth()->user()->emp_name ?? 'Admin', 
                'query_title'    => $title, 
                'status'         => $request->common_status ?? ($request->status[$key] ?? 0),
                'assigned_date'  => $assignDate,
                'target_date'    => $targetDate,
                'priority'       => $request->priority[$key] ?? 'Medium',
                'query_details'  => $request->query_details[$key] ?? '',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 2. SYNC TO tbl_dailyreport (Automatically)
            if ($finalEmployee !== 'Not Assigned') {
                \DB::table('tbl_dailyreport')->insert([
                    'date'             => $assignDate,
                    'emp_name'         => $finalEmployee, // Target Employee
                    'project_name'     => $title,         // Use Query Title as Project
                    'task_description' => "ADMIN ASSIGNED: " . ($request->query_details[$key] ?? ''),
                    'status'           => 'Pending',      // Initial status for report
                    'work_type'        => 'Office',
                    'created_at'       => now(),
                ]);
            }
        }
    }
    return redirect()->route('admin.queries.list')->with('success', 'Queries assigned and synced to Daily Reports!');
}

    public function edit(Query $query)
    {
        $projects = ProjectMaster::all();
        $employees = Employee::where('designation', '!=', 'Project Manager')->get();
          $products = \App\Models\Product::all(); // ADD THIS LINE
        return view('admin.queries.edit', compact('query', 'projects', 'employees','products'));
    }

    public function destroy(Query $query)
    {
        $query->delete();
        return redirect()->route('admin.queries.list')->with('success', 'Query deleted successfully!');
    }


    // --- EMPLOYEE FUNCTIONS ---



    public function updateStatus(Request $request, Query $query)
    {
        // This is a simple status updater. We can make it more complex later.
        if ($query->status == 0) { // If status is 'New'
            $query->status = 1; // Change to 'In Progress'
            $query->start_date = Carbon::now()->format('Y-m-d');
        }
        $query->save();
        return redirect()->route('employee.queries')->with('success', 'Query Updated Successfully!');
    }


    
    public function index()
    {
        // Get queries assigned to the logged-in employee
        $queries = Query::where('user_id', Auth::id())->with('project')->get();

        return view('employee.queries.index', compact('queries')); // Create this view
    }
    
 
    
    public function update(Request $request, $id)
{
    $request->validate([
        'projectname' => 'required_without:productname', 
        'productname' => 'required_without:projectname',
        
        // Ensure these are marked as nullable
        'assigned_date' => 'nullable|date', 
        'target_date' => 'nullable|date',
        
        'priority'    => 'required',
    ], [
        'projectname.required_without' => 'Please select either a Project or a Product.',
        'productname.required_without' => 'Please select either a Project or a Product.',
    ]);

    $query = \App\Models\Query::find($id);

    $query->projectname   = $request->projectname;
    $query->productname   = $request->productname;
    $query->assigned_to   = $request->assigned_to; // This will now save as NULL correctly
    $query->priority      = $request->priority;
    $query->assigned_date = $request->assigned_date;
    $query->target_date   = $request->target_date;   // This will now save as NULL correctly
    $query->query_details = $request->query_details;
    $query->status        = $request->status;
    
    $query->save();

    return redirect()->route('admin.queries.list')->with('success', 'Query updated successfully!');
}


public function employeeUpdate(Request $request, $id)
{
    // 1. Fetch the assigned date from DB to ensure start_date isn't before it
    $query = \DB::table('tbl_queries')->where('id', $id)->first();

    // 2. Add Validation
    $request->validate([
        'start_date' => 'required|date|after_or_equal:' . $query->assigned_date,
        'end_date'   => 'nullable|date|after_or_equal:start_date',
        'hours_spent'=> 'required|numeric|min:0.1',
        'progress_percentage' => 'required|integer|min:0|max:100',
    ], [
        // Custom Error Messages
        'start_date.after_or_equal' => 'Start Date cannot be before the Assigned Date (' . date('d-m-Y', strtotime($query->assigned_date)) . ').',
        'end_date.after_or_equal'   => 'End Date cannot be earlier than the Start Date.',
    ]);

    // 3. If validation passes, update the main table
    \DB::table('tbl_queries')->where('id', $id)->update([
        'status' => $request->status,
        'hours_spent' => $request->hours_spent,
        'progress_percentage' => $request->progress_percentage,
        'remarks' => $request->remarks,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'updated_at' => now()
    ]);

    // 4. Update logs...
    \DB::table('tbl_query_logs')->insert([
        'query_id' => $id,
        'percentage' => $request->progress_percentage,
        'remarks' => $request->remarks,
        'hours_spent' => $request->hours_spent,
        'created_at' => now()
    ]);

    return back()->with('success', 'Update recorded!');
}

public function employeeList()
{
    // Filter queries by the logged-in employee's name
    $queries = Query::where('assigned_to', auth()->user()->emp_name)
                    ->orderBy('assigned_date', 'desc')
                    ->get();
                    
    return view('employee.queries', compact('queries'));
}

             public function addProjectQuery() {
    $projects = \App\Models\ProjectMaster::all();
    $employees = \App\Models\Employee::where('designation', '!=', 'Project Manager')->get();
    $products = \App\Models\Product::all(); // Still sending products because you want ALL fields
    return view('admin.queries.project_add', compact('projects', 'employees', 'products'));
}

public function addProductQuery() {
    $projects = \App\Models\ProjectMaster::all();
    $employees = \App\Models\Employee::where('designation', '!=', 'Project Manager')->get();
    $products = \App\Models\Product::all();
    return view('admin.queries.product_add', compact('projects', 'employees', 'products'));
}



// Add this to your QueryController.php

public function employeeAdd()
{
    $projects = \DB::table('tbl_projectmaster')->get();
    $products = \DB::table('tbl_productmaster')->get();
    return view('employee.add_query', compact('projects', 'products'));
}

public function employeeStore(Request $request)
{
    // Get the logged-in employee's name
    $current_user = auth()->user()->emp_name; 

    foreach ($request->projectname as $key => $val) {
        if (!empty($request->projectname[$key]) || !empty($request->productname[$key])) {
            
            // Auto-generate title
            $auto_title = $request->projectname[$key] ?? ($request->productname[$key] ?? 'Task');

            \DB::table('tbl_queries')->insert([
                'projectname'   => $request->projectname[$key],
                'productname'   => $request->productname[$key],
                'assigned_to'   => $current_user,      // The person it is assigned to
                'emp_name'      => $current_user,      // THE MISSING FIELD: The person who created it
                'query_title'   => $auto_title,         
                'query_details' => $request->query_details[$key],
                'assigned_date' => $request->assigned_date[$key],
                'target_date'   => $request->target_date[$key],
                'priority'      => $request->priority[$key],
                'status'        => $request->status[$key],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }

    return redirect()->route('employee.queries')->with('success', 'Queries added successfully!');
}
}




