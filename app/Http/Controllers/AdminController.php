<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Employee;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Project;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function employeeList() {
        // Fetch all employees from DB for the table (Image 2)
        $employees = Employee::all();
        return view('admin.employee_list', compact('employees'));
    }

    public function addEmployee() {
        return view('admin.employee_add'); // Shows the form (Images 5 & 6)
    }



    public function projectList(Request $request) {
    // 1. Start a query on the Project model
    $query = \App\Models\Project::query();

    // 2. Filter by Status (Confirmation)
    // If user selects 'Pending' or 'Confirmed'
    if ($request->filled('status') && $request->status != 'All') {
        $query->where('projectconfirmation', $request->status);
    }

    // 3. Filter by Search (Name or Description)
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('projectname', 'like', '%' . $searchTerm . '%')
              ->orWhere('description', 'like', '%' . $searchTerm . '%');
        });
    }

    // 4. Get the filtered results
    $projects = $query->latest()->get();

    return view('admin.project_master', compact('projects'));
}

    /*
     public function projectList() {
        $projects = Project::all(); // Fetches all projects
        return view('admin.project_master', compact('projects'));
    }
*/

      public function addProduct()
    {
        return view('admin.product_add');
    }


        public function addProject() {
        return view('admin.project_add');
    }
    

    public function storeProject(Request $request)
{
    // 1. Validation
    $request->validate([
        'projectname' => 'required|max:255',
        'clientname'  => 'required|max:255', // Add this validation
    ]);

    // 2. Save to Database
    \App\Models\Project::create([
        'projectname' => $request->projectname,
        'clientname'  => $request->clientname, // ADD THIS LINE
        'description' => $request->description,
        'status'      => $request->status ?? 'Active',
    ]);

    return redirect()->route('admin.project.list')->with('success', 'Project created successfully!');
}


public function deleteProject($id) {
    // Find the project or show a 404 error
    $project = \App\Models\Project::findOrFail($id);
    
    // Delete the project
    $project->delete();

    // Redirect back to the list with success message
    return redirect()->route('admin.project.list')->with('success', 'Project deleted successfully!');
}

// Also add this if you want "Delete Selected" to work for PROJECTS too
public function deleteSelectedProjects(Request $request)
{
    $ids = $request->ids; // This receives the array from AJAX
    if(!empty($ids)){
        // Make sure you use the Project model here
        \App\Models\Project::whereIn('id', $ids)->delete();
        return response()->json(['success' => "Selected projects deleted successfully."]);
    }
    return response()->json(['error' => "Please select at least one project."]);
}


public function storeProduct(Request $request) {
    $request->validate([
        'productname' => 'required',
        'amt'         => 'required|numeric',
    ]);

    \App\Models\Product::create([
        'productname'      => $request->productname,
        'category'         => $request->category,
        'amt'              => $request->amt,
        'gst'              => $request->gst ?? 0,
        'description'      => $request->description ?? '',
        'hardware_details' => $request->hardware_details ?? '',
        'software_details' => $request->software_details ?? '',
        'hardwareprice'    => 0, // Providing the default value the DB wants
    ]);

    return redirect()->route('admin.product.list')->with('success', 'Product saved successfully!');
}


public function deleteProduct($id) {
    $product = Product::findOrFail($id);
    $product->delete();

    return redirect()->route('admin.product.list')->with('success', 'Product deleted successfully!');
}

    


// 1. Show the Edit Form with existing data
    public function editProject($id)
    {
        // Find the project or crash if not found
        $project = \App\Models\Project::findOrFail($id);
        
        return view('admin.edit_project', compact('project'));
    }

    // 2. Save the updated data
    public function updateProject(Request $request, $id)
    {
        $project = \App\Models\Project::findOrFail($id);

        $request->validate([
            'projectname' => 'required',
            'clientname'  => 'required',
        ]);

        $project->update([
            'projectname'         => $request->projectname,
            'clientname'          => $request->clientname,
            'clientcontactno'     => $request->clientcontactno,
            'platform'            => $request->platform,
            'language'            => $request->language,
            'projectconfirmation' => $request->projectconfirmation,
            'description'         => $request->description,
            'status'              => $request->status,
        ]);

        return redirect()->route('admin.project.list')->with('success', 'Project updated successfully!');
    }

         
    public function productList(Request $request) {
    // 1. Start query
    $query = \App\Models\Product::query();

    // 2. Filter by Category if selected
    if ($request->filled('category') && $request->category != 'All') {
        $query->where('category', $request->category);
    }

    // 3. Get results
    $products = $query->latest()->get();
    
    return view('admin.product_master', compact('products'));
}

     



/*
       public function productList() {
        // Fetch all products from the database
        $products = Product::all(); 
        
        // Return the view and pass the products data to it
        // Note: Change 'admin.product_list' if your view file is named 'product_master.blade.php' 
        return view('admin.product_master', compact('products'));
    }


*/

  public function storeEmployee(Request $request) {
    // 1. Validation
    $request->validate([
        'emp_name' => 'required',
        'username' => 'required|unique:tbl_employeemaster,username',
        'password' => 'required|confirmed|min:6', // Looks for 'password_confirmation' in HTML
        'password_confirmation' => 'required|same:password',
    ]);

    $employee = new Employee();
    $employee->emp_name    = $request->emp_name;
    $employee->mobile      = $request->mobile;
    $employee->email       = $request->email;
    $employee->address     = $request->address;
    $employee->city        = $request->city;
    $employee->state       = $request->state;
    $employee->pincode     = $request->pincode;
    $employee->department  = $request->department;
    $employee->designation = $request->designation;
    
    // IMPORTANT: Make sure these names match your <input name="..."> exactly
    $employee->accno       = $request->accno; 
    $employee->bankname    = $request->bankname;
    $employee->ifsc        = $request->ifsc;
    $employee->branch      = $request->branch;
    
    $employee->username    = $request->username;
    $employee->password    = bcrypt($request->password);
    $employee->status      = 1; 
    $employee->created_at  = now();

    $employee->confirm_password = bcrypt($request->password); 

    // Photo Upload (Match the name in your <input type="file" name="photo">)
    if($request->hasFile('photo')){
        $file = $request->file('photo');
        $filename = 'photo_'.time().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('uploads/employees'), $filename);
        $employee->photo = $filename;
    }

    $employee->save();

    return redirect()->route('admin.employee.list')->with('success', 'Employee Added Successfully!');
}


    // 1. Show the Edit Form with existing Employee data
    public function editEmployee($id)
    {
        // Find the employee by ID
        $employee = Employee::findOrFail($id);
        
        // Return the edit view (Make sure you create employee_edit.blade.php!)
        return view('admin.employee_edit', compact('employee'));
    }

      
public function updateEmployee(Request $request, $id)
{
    $employee = Employee::findOrFail($id);

    $request->validate([
        'emp_name' => 'required',
        'mobile'   => 'required',
    ]);

    $employee->emp_name    = $request->emp_name;
    $employee->mobile      = $request->mobile;
    $employee->department  = $request->department ?? $employee->department;
    $employee->designation = $request->designation ?? $employee->designation;
    $employee->email       = $request->email ?? $employee->email;
    $employee->address     = $request->address ?? $employee->address;
    $employee->city        = $request->city ?? $employee->city;
    $employee->state       = $request->state ?? $employee->state;
    $employee->pincode     = $request->pincode ?? $employee->pincode;
    
    if ($request->filled('password')) {
        $employee->password = bcrypt($request->password);
        $employee->confirm_password = bcrypt($request->password); 
    }

    $employee->save();

    return redirect()->route('admin.employee.list')->with('success', 'Employee Updated Successfully!');
}


    // 3. Delete an Employee
    public function deleteEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('admin.employee.list')->with('success', 'Employee Deleted Successfully!');
    }


       // Add these methods inside your AdminController class

// 1. Show the Edit Form with existing data
public function editProduct($id)
{
    $product = Product::findOrFail($id);
    return view('admin.edit_product', compact('product'));
}

// 2. Save the updated Product data


public function updateProduct(Request $request, $id)
{
    $product = \App\Models\Product::findOrFail($id);

    $request->validate([
        'productname' => 'required',
        'amt'         => 'required|numeric',
    ]);

    $product->update([
        'productname'      => $request->productname,
        'category'         => $request->category,
        'amt'              => $request->amt,
        'gst'              => $request->gst ?? 0,
        'description'      => $request->description ?? '',
        'hardware_details' => $request->hardware_details ?? '',
        'software_details' => $request->software_details ?? '',
        'hardwareprice'    => 0, // Added here too to prevent future errors
    ]);

    return redirect()->route('admin.product.list')->with('success', 'Product updated successfully!');
}


// 3. Handle Bulk Delete
public function deleteSelectedProducts(Request $request)
{
    $ids = $request->ids;
    if(!empty($ids)){
        Product::whereIn('id', $ids)->delete();
        return response()->json(['success' => "Selected products deleted successfully."]);
    }
    return response()->json(['error' => "Please select at least one product."]);
}

// Add this inside the AdminController class

public function updateStatus(Request $request)
{
    // PREVENTION: Check if the ID being toggled is the same as the logged-in user
    if ($request->id == auth()->user()->id) {
        return response()->json(['error' => 'You cannot disable your own account!'], 403);
    }

    $employee = Employee::find($request->id);
    
    if ($employee) {
        $employee->status = $request->status;
        $employee->save();
        return response()->json(['success' => 'Status updated successfully.']);
    }

    return response()->json(['error' => 'Employee not found.'], 404);
}




public function leaveIndex()
{
    $employees = \DB::table('tbl_employeemaster')->select('emp_name')->get();
    $leaves = \DB::table('tbl_leave_requests')->orderBy('leave_date', 'desc')->get();
    
    // FETCH HOLIDAYS TOO
    $holidays = \DB::table('tbl_holiday_master')->orderBy('holiday_date', 'desc')->get();

    return view('admin.leave_management', compact('employees', 'leaves', 'holidays'));
}

public function leaveStore(Request $request)
{
    // 1. Validation
    $request->validate([
        'employee_name' => 'required',
        'leave_date'    => 'required|date',
        'leave_type'    => 'required',
    ]);

    // 2. NEW STEP: Find the employee ID from the Employee Master table
    $employee = DB::table('tbl_employeemaster')
                  ->where('emp_name', $request->employee_name)
                  ->first();

    // 3. Insert into the database including the employee_id
    DB::table('tbl_leave_requests')->insert([
        'employee_id'   => $employee ? $employee->id : 0, // Add the ID here
        'employee_name' => $request->employee_name,
        'leave_date'    => $request->leave_date,
        'leave_type'    => $request->leave_type,
        'details'       => $request->details,
        'applied_by'    => auth()->user()->emp_name ?? 'Admin',
        'status'        => 'Approved',
        'created_at'    => now(),
    ]);

    return back()->with('success', 'Leave record added successfully!');
}

public function leaveDelete($id)
{
    \DB::table('tbl_leave_requests')->where('id', $id)->delete();
    return back()->with('success', 'Record deleted!');
}

public function holidayStore(Request $request)
{
    $request->validate([
        'holiday_name' => 'required',
        'holiday_date' => 'required|date',
    ]);

    \DB::table('tbl_holiday_master')->insert([
        'holiday_name' => $request->holiday_name,
        'holiday_date' => $request->holiday_date,
        'holiday_type' => $request->holiday_type,
        'year'         => date('Y', strtotime($request->holiday_date)),
        'status'       => 1,
        'created_at'   => now(),
    ]);

    return back()->with('success', 'Master Holiday added successfully!');
}
public function attendanceReport(Request $request)
{
    // 1. Get Logged in User (Fixes the Undefined $user error)
    $user = auth()->user();

    // 2. Set Date Range
    $fromDate = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
    $toDate = $request->to_date ?? now()->endOfMonth()->format('Y-m-d');

    $start = Carbon::parse($fromDate);
    $end = Carbon::parse($toDate);
    $totalDaysInRange = $start->diffInDays($end) + 1;

    // 3. Sundays Count
    $sundays = 0;
    $tempDate = $start->copy();
    while ($tempDate->lte($end)) {
        if ($tempDate->isSunday()) $sundays++;
        $tempDate->addDay();
    }

    // 4. Holidays Count
    $holidayCount = DB::table('tbl_holiday_master')->whereBetween('holiday_date', [$fromDate, $toDate])->count();
    $workingDaysLimit = $totalDaysInRange - $sundays - $holidayCount;

    $employees = Employee::all();
    $reportData = [];

    foreach ($employees as $emp) {
        // 5. Present Count (Fixed Ambiguity by using tbl_query_logs. prefix)
        $taskLogs = DB::table('tbl_query_logs')
            ->join('tbl_queries', 'tbl_query_logs.query_id', '=', 'tbl_queries.id')
            ->where('tbl_queries.assigned_to', $emp->emp_name)
            ->whereBetween('tbl_query_logs.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
            ->select('tbl_query_logs.*', 'tbl_queries.query_title')
            ->orderBy('tbl_query_logs.created_at', 'desc')
            ->get();

        $actualWorkingCount = $taskLogs->pluck('created_at')->map(function($d) {
            return Carbon::parse($d)->format('Y-m-d');
        })->unique()->count();

        // 6. Other Stats (WFH, OT, Permission)
        $stats = DB::table('tbl_dailyreportspecial')
            ->where('emp_name', $emp->emp_name)
            ->whereBetween('date', [$fromDate, $toDate])
            ->selectRaw("
                COUNT(CASE WHEN work_type = 'WFH' THEN 1 END) as wfh,
                COUNT(CASE WHEN work_type = 'OT' THEN 1 END) as ot,
                COUNT(CASE WHEN work_type = 'Permission' THEN 1 END) as permission
            ")->first();

        // 7. Leave Count
        $leaveCount = DB::table('tbl_leave_requests')
            ->where('employee_name', $emp->emp_name)
            ->whereBetween('leave_date', [$fromDate, $toDate])
            ->count();

        // 8. Payable Calculation: (Task Days + WFH + Sundays + Holidays)
        $payableDays = $actualWorkingCount + ($stats->wfh ?? 0) + $sundays + $holidayCount;

        $reportData[] = [
            'id' => $emp->id,
            'name' => $emp->emp_name,
            'total_days' => $totalDaysInRange,
            'sundays' => $sundays,
            'holidays' => $holidayCount,
            'working_days' => $workingDaysLimit,
            'leave' => $leaveCount,
            'wfh' => $stats->wfh ?? 0,
            'ot' => $stats->ot ?? 0,
            'permission' => $stats->permission ?? 0,
            'actual' => $actualWorkingCount,
            'logs' => $taskLogs,
            'payable' => $payableDays
        ];
    }

    // Pass 'user' into the compact function below
    return view('admin.attendance_report', compact('reportData', 'fromDate', 'toDate', 'user'));
}

public function attendanceExport(Request $request)
{
    // 1. Set Date Range
    $fromDate = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
    $toDate = $request->to_date ?? now()->endOfMonth()->format('Y-m-d');
    $start = \Carbon\Carbon::parse($fromDate);
    $end = \Carbon\Carbon::parse($toDate);
    $totalDays = $start->diffInDays($end) + 1;

    // 2. Sundays Count
    $sundays = 0;
    $tempDate = $start->copy();
    while ($tempDate->lte($end)) {
        if ($tempDate->isSunday()) $sundays++;
        $tempDate->addDay();
    }

    // 3. Holidays Count
    $holidayCount = \Illuminate\Support\Facades\DB::table('tbl_holiday_master')
        ->whereBetween('holiday_date', [$fromDate, $toDate])->count();
    $workingDaysLimit = $totalDays - $sundays - $holidayCount;

    $employees = \App\Models\Employee::all();
    $reportData = [];

    // 4. Calculate Data for each employee
    foreach ($employees as $emp) {
        $actualWorkingCount = \Illuminate\Support\Facades\DB::table('tbl_query_logs')
            ->join('tbl_queries', 'tbl_query_logs.query_id', '=', 'tbl_queries.id')
            ->where('tbl_queries.assigned_to', $emp->emp_name)
            ->whereBetween('tbl_query_logs.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
            ->select(\Illuminate\Support\Facades\DB::raw('DATE(tbl_query_logs.created_at) as log_date'))->distinct()->get()->count();

        $stats = \Illuminate\Support\Facades\DB::table('tbl_dailyreportspecial')
            ->where('emp_name', $emp->emp_name)->whereBetween('date', [$fromDate, $toDate])
            ->selectRaw("COUNT(CASE WHEN work_type = 'WFH' THEN 1 END) as wfh, COUNT(CASE WHEN work_type = 'OT' THEN 1 END) as ot, COUNT(CASE WHEN work_type = 'Permission' THEN 1 END) as permission")->first();

        $leaveCount = \Illuminate\Support\Facades\DB::table('tbl_leave_requests')
            ->where('employee_name', $emp->emp_name)->whereBetween('leave_date', [$fromDate, $toDate])->count();

        $reportData[] = [
            'name' => $emp->emp_name,
            'total_days' => $totalDays,
            'sundays' => $sundays,
            'holidays' => $holidayCount,
            'working_days' => $workingDaysLimit,
            'leave' => $leaveCount,
            'wfh' => $stats->wfh ?? 0,
            'ot' => $stats->ot ?? 0,
            'permission' => $stats->permission ?? 0,
            'actual' => $actualWorkingCount,
            'payable' => ($actualWorkingCount + $sundays + $holidayCount)
        ];
    }

    // 5. Generate Excel-compatible CSV File
    $fileName = 'Attendance_Report_' . $fromDate . '.csv';
    $headers = [
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $callback = function() use($reportData) {
        $file = fopen('php://output', 'w');
        
        // Write the Excel Column Headers
        fputcsv($file, ['Employee Name', 'Total Days', 'Sundays', 'Holidays', 'Working Days Limit', 'Leave', 'WFH', 'OT', 'Permission', 'Actual Working Days', 'Payable Days']);

        // Write the Data Rows
        foreach ($reportData as $row) {
            fputcsv($file, [
                $row['name'],
                $row['total_days'],
                $row['sundays'],
                $row['holidays'],
                $row['working_days'],
                $row['leave'],
                $row['wfh'],
                $row['ot'],
                $row['permission'],
                $row['actual'],
                $row['payable']
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

public function attendancePdf(Request $request)
{
    // 1. Replicate the calculation logic to get accurate data
    $fromDate = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
    $toDate = $request->to_date ?? now()->endOfMonth()->format('Y-m-d');
    $start = Carbon::parse($fromDate);
    $end = Carbon::parse($toDate);
    $totalDays = $start->diffInDays($end) + 1;

    $sundays = 0;
    $tempDate = $start->copy();
    while ($tempDate->lte($end)) {
        if ($tempDate->isSunday()) $sundays++;
        $tempDate->addDay();
    }

    $holidayCount = DB::table('tbl_holiday_master')->whereBetween('holiday_date', [$fromDate, $toDate])->count();
    $workingDaysLimit = $totalDays - $sundays - $holidayCount;

    $employees = Employee::all();
    $reportData = [];

    foreach ($employees as $emp) {
        $actualWorkingCount = DB::table('tbl_query_logs')
            ->join('tbl_queries', 'tbl_query_logs.query_id', '=', 'tbl_queries.id')
            ->where('tbl_queries.assigned_to', $emp->emp_name)
            ->whereBetween('tbl_query_logs.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
            ->select(DB::raw('DATE(tbl_query_logs.created_at) as log_date'))->distinct()->get()->count();

        $stats = DB::table('tbl_dailyreportspecial')->where('emp_name', $emp->emp_name)->whereBetween('date', [$fromDate, $toDate])
            ->selectRaw("COUNT(CASE WHEN work_type = 'WFH' THEN 1 END) as wfh, COUNT(CASE WHEN work_type = 'OT' THEN 1 END) as ot, COUNT(CASE WHEN work_type = 'Permission' THEN 1 END) as permission")->first();

        $leaveCount = DB::table('tbl_leave_requests')->where('employee_name', $emp->emp_name)->whereBetween('leave_date', [$fromDate, $toDate])->count();

        $reportData[] = [
            'name' => $emp->emp_name,
            'total_days' => $totalDays,
            'sundays' => $sundays,
            'holidays' => $holidayCount,
            'working_days' => $workingDaysLimit,
            'leave' => $leaveCount,
            'wfh' => $stats->wfh ?? 0,
            'ot' => $stats->ot ?? 0,
            'permission' => $stats->permission ?? 0,
            'actual' => $actualWorkingCount,
            'payable' => ($actualWorkingCount + $sundays + $holidayCount)
        ];
    }

    // 2. Load the PDF view and download
    $pdf = Pdf::loadView('attendance_pdf_download', compact('reportData', 'fromDate', 'toDate'))
              ->setPaper('a4', 'landscape'); // Landscape fits all 12 columns
              
    return $pdf->download('Attendance_Report_'.$fromDate.'.pdf');
}

public function dailyReportIndex()
{
    $user = auth()->user();
    
    // 1. Fetch only the logged-in user's reports (Matching "My Report" section)
    $myReports = DB::table('tbl_dailyreportspecial')
                ->where('emp_name', $user->emp_name)
                ->orderBy('date', 'desc')
                ->get();

    return view("admin.daily_report_special", compact('myReports', 'user'));
}

public function dailyReportStore(Request $request)
{
    $request->validate([
        'report' => 'required',
        'work_type' => 'required'
    ]);

    // 2. Insert into the special daily report table
    DB::table('tbl_dailyreportspecial')->insert([
        'employee_id' => auth()->user()->id,
        'emp_name'    => auth()->user()->emp_name,
        'date'        => now()->format('Y-m-d'),
        'report'      => $request->report,
        'work_type'   => $request->work_type,
        'permission'  => '-',
        'created_at'  => now(),
        'updated_at'  => now(),
        'created_ip'  => $request->ip(),
        'updated_ip'  => $request->ip(),
    ]);

    return back()->with('success', 'Daily update submitted successfully!');
}



public function dailyReportUpdate(Request $request, $id)
{
    $request->validate(['report' => 'required', 'work_type' => 'required']);

    DB::table('tbl_dailyreportspecial')->where('id', $id)->update([
        'report' => $request->report,
        'work_type' => $request->work_type,
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Report updated successfully!');
}

public function dailyReportDelete($id)
{
    DB::table('tbl_dailyreportspecial')->where('id', $id)->delete();
    return back()->with('success', 'Report deleted successfully!');
}


public function teamReportView(Request $request)
{
    // Default to leader tab if nothing is selected
    $tab = $request->query('tab', 'leader');

    $employees = DB::table('tbl_employeemaster')->select('emp_name')->get();
    $projects = DB::table('tbl_projectmaster')->select('projectname')->get();

    // Leader Tab uses tbl_dailyreportspecial, Production Tab uses tbl_dailyreport
    if ($tab == 'leader') {
        $query = DB::table('tbl_dailyreportspecial');
    } else {
        $query = DB::table('tbl_dailyreport');
    }

    // Apply Filters
    if ($request->filled('employee')) {
        $query->where('emp_name', $request->employee);
    }
    if ($request->filled('from_date')) {
        $query->where('date', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $query->where('date', '<=', $request->to_date);
    }

    $reports = $query->orderBy('date', 'desc')->get();

    return view('admin.team_report_view', compact('reports', 'employees', 'projects', 'tab'));
}


// METHOD TO HANDLE EXCEL IMPORT
 public function teamReportExportExcel(Request $request)
    {
        $tab = $request->query('tab', 'leader');
        $query = ($tab == 'leader') ? \Illuminate\Support\Facades\DB::table('tbl_dailyreportspecial') : \Illuminate\Support\Facades\DB::table('tbl_dailyreport');

        // Apply filters
        if ($request->filled('employee')) $query->where('emp_name', $request->employee);
        if ($request->filled('from_date')) $query->where('date', '>=', $request->from_date);
        if ($request->filled('to_date')) $query->where('date', '<=', $request->to_date);
        if ($tab == 'production') {
            if ($request->filled('status')) $query->where('status', $request->status);
            if ($request->filled('project')) $query->where('project_name', $request->project);
        }

        $reports = $query->orderBy('date', 'desc')->get();

        $fileName = 'Team_Report_' . ucfirst($tab) . '_' . date('Y-m-d') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($reports, $tab) {
            $file = fopen('php://output', 'w');
            if ($tab == 'production') {
                fputcsv($file, ['Date', 'Employee', 'Project', 'Module', 'Task', 'Status', 'Target Hrs', 'Start', 'End', 'Work Type', 'Permission', 'Remarks']);
                foreach ($reports as $row) {
                    fputcsv($file, [
                        $row->date ? date('d/m/Y', strtotime($row->date)) : '-',
                        $row->emp_name,
                        $row->project_name ?? '-',
                        $row->module_name ?? '-',
                        $row->task_description ?? '-',
                        $row->status ?? 'Pending',
                        $row->target_hours ?? '0',
                        $row->task_start ?? '-',
                        $row->task_end ?? '-',
                        $row->work_type ?? 'Office',
                        $row->permission ?? '-',
                        $row->remarks ?? '-'
                    ]);
                }
            } else {
                fputcsv($file, ['Date', 'Employee', 'Report', 'Permission', 'Work Type']);
                foreach ($reports as $row) {
                    fputcsv($file, [
                        $row->date ? date('d/m/Y', strtotime($row->date)) : '-',
                        $row->emp_name,
                        $row->report ?? '-',
                        $row->permission ?? '-',
                        $row->work_type ?? 'Office'
                    ]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


      public function teamReportExportPdf(Request $request)
    {
        $tab = $request->query('tab', 'leader');
        $query = ($tab == 'leader') ? \Illuminate\Support\Facades\DB::table('tbl_dailyreportspecial') : \Illuminate\Support\Facades\DB::table('tbl_dailyreport');

        // Apply filters
        if ($request->filled('employee')) $query->where('emp_name', $request->employee);
        if ($request->filled('from_date')) $query->where('date', '>=', $request->from_date);
        if ($request->filled('to_date')) $query->where('date', '<=', $request->to_date);
        if ($tab == 'production') {
            if ($request->filled('status')) $query->where('status', $request->status);
            if ($request->filled('project')) $query->where('project_name', $request->project);
        }

        $reports = $query->orderBy('date', 'desc')->get();

        $pdf = Pdf::loadView('admin.team_report_pdf', compact('reports', 'tab'))
                  ->setPaper('a4', 'landscape');
                  
        return $pdf->download('Team_Report_' . ucfirst($tab) . '_' . date('Y-m-d') . '.pdf');
    }


}


