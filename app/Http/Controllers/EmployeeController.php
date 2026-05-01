<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 

class EmployeeController extends Controller
{
    public function dashboard()
{
    $user = Auth::user();
    $today = Carbon::today()->toDateString();
    $month = Carbon::now()->month;
    $year = Carbon::now()->year;

    $data = [
        // 1. Today's Reports
        'todayCount'      => DB::table('tbl_dailyreport')
                            ->where('emp_name', $user->emp_name)
                            ->whereDate('date', $today)
                            ->count(),

        // 2. Monthly Reports (Total entries this month)
        'monthlyCount'    => DB::table('tbl_dailyreport')
                            ->where('emp_name', $user->emp_name)
                            ->whereMonth('date', $month)
                            ->whereYear('date', $year)
                            ->count(),

        // 3. Leave count (Fetched from tbl_leave_requests)
        'leaveCount'      => DB::table('tbl_leave_requests')
                            ->where('employee_name', $user->emp_name)
                            ->count(),

        // 4. WFH count from daily reports
        'wfhCount'        => DB::table('tbl_dailyreport')
                            ->where('emp_name', $user->emp_name)
                            ->where('work_type', 'WFH')
                            ->count(),

        // 5. OT count from daily reports
        'otCount'         => DB::table('tbl_dailyreport')
                            ->where('emp_name', $user->emp_name)
                            ->where('work_type', 'OT')
                            ->count(),

        // 6. Permission count from daily reports
        'permissionCount' => DB::table('tbl_dailyreport')
                            ->where('emp_name', $user->emp_name)
                            ->where('work_type', 'Permission')
                            ->count(),
    ];

    // Fetch the 5 most recent tasks (not just 3, for a better view)
    $recentReports = DB::table('tbl_dailyreport')
        ->where('emp_name', $user->emp_name)
        ->orderBy('date', 'desc')
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    return view('employee.dashboard', compact('data', 'recentReports', 'user'));
}

    
    public function attendanceReport(Request $request)
    {
        $user = Auth::user(); // This gets the logged-in employee (e.g., Santhiya)

        // 1. Set Date Range (Default to current month)
        $fromDate = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $request->to_date ?? now()->endOfMonth()->format('Y-m-d');

        $start = Carbon::parse($fromDate);
        $end = Carbon::parse($toDate);
        $totalDaysInRange = $start->diffInDays($end) + 1;

        // 2. Calculate Sundays & Holidays
        $sundays = 0;
        $tempDate = $start->copy();
        while ($tempDate->lte($end)) {
            if ($tempDate->isSunday()) $sundays++;
            $tempDate->addDay();
        }
        $holidayCount = DB::table('tbl_holiday_master')->whereBetween('holiday_date', [$fromDate, $toDate])->count();
        $workingDaysLimit = $totalDaysInRange - $sundays - $holidayCount;

        // 3. Fetch Task Logs (Actual Work) for this employee
        $taskLogs = DB::table('tbl_query_logs')
            ->join('tbl_queries', 'tbl_query_logs.query_id', '=', 'tbl_queries.id')
            ->where('tbl_queries.assigned_to', $user->emp_name)
            ->whereBetween('tbl_query_logs.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
            ->select('tbl_query_logs.*', 'tbl_queries.query_title')
            ->get();

        $actualWorkingCount = $taskLogs->pluck('created_at')->map(function($d) {
            return Carbon::parse($d)->format('Y-m-d');
        })->unique()->count();

        // 4. Fetch Stats (WFH, OT, Leave)
        $stats = DB::table('tbl_dailyreportspecial')->where('emp_name', $user->emp_name)->whereBetween('date', [$fromDate, $toDate])
            ->selectRaw("COUNT(CASE WHEN work_type = 'WFH' THEN 1 END) as wfh, COUNT(CASE WHEN work_type = 'OT' THEN 1 END) as ot, COUNT(CASE WHEN work_type = 'Permission' THEN 1 END) as permission")->first();

        $leaveCount = DB::table('tbl_leave_requests')->where('employee_name', $user->emp_name)->whereBetween('leave_date', [$fromDate, $toDate])->count();

        // 5. Pack data for the view
        $reportData = [[
            'id' => $user->id,
            'name' => $user->emp_name,
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
            'payable' => ($actualWorkingCount + $sundays + $holidayCount)
        ]];

        return view('employee.attendance_report', compact('reportData', 'fromDate', 'toDate', 'user'));
    }

  
    
public function dailyReportIndex()
{
    $emp_name = auth()->user()->emp_name;

    // 1. Get standard Daily Reports
    $reports = \DB::table('tbl_dailyreport')
        ->where('emp_name', $emp_name)
        ->select(
            'date', 
            'project_name', 
            'module_name', 
            'task_description', 
            'status', 
            'target_hours', 
            'task_start', 
            'task_end', 
            'work_type', 
            'permission', 
            'remarks', 
            \DB::raw("'Report' as source") // Name this 'source'
        );

    // 2. Get Assigned Queries (IMAGE 1 DATA)
    $queries = \DB::table('tbl_queries')
        ->where('assigned_to', $emp_name)
        ->select(
            'assigned_date as date', 
            'projectname as project_name', 
            'query_title as module_name', 
            'query_details as task_description', 
            \DB::raw("CASE WHEN status = 0 THEN 'Pending' WHEN status = 1 THEN 'In-progress' ELSE 'Completed' END as status"),
            \DB::raw("0 as target_hours"), 
            'start_date as task_start', 
            'end_date as task_end', 
            \DB::raw("'Office' as work_type"), 
            \DB::raw("'-' as permission"), 
            'remarks',
            \DB::raw("'Query' as source") // Name this 'source'
        );

    // 3. Combine and send to view
    $combinedData = $reports->union($queries)->orderBy('date', 'desc')->get();

    return view('employee.daily_report_view', ['reports' => $combinedData]);
}

public function dailyReportCreate()
{
    $user = auth()->user();
    // Fetch projects for the dropdown
    $projects = \DB::table('tbl_projectmaster')->select('projectname')->get();
    return view('employee.daily_report_add', compact('user', 'projects'));
}

public function dailyReportStore(Request $request)
{
    // 1. Get logged-in user name
    $emp_name = auth()->user()->emp_name;

    // 2. Loop through the task arrays
    foreach ($request->project_name as $key => $val) {
        if (!empty($request->project_name[$key])) {
            \DB::table('tbl_dailyreport')->insert([
                'date'             => $request->report_date,
                'emp_name'         => $emp_name,
                'work_type'        => $request->work_type,
                'leave_reason'     => $request->reason, // Mapped to DB column
                'permission'       => $request->permission_duration, // Mapped to DB column
                
                'project_name'     => $request->project_name[$key],
                'module_name'      => $request->module_name[$key],
                'task_description' => $request->task_description[$key],
                'status'           => $request->status[$key],
                'target_hours'     => $request->target_hours[$key] ?? 0,
                'remarks'          => $request->remarks[$key],
                'task_start'       => $request->task_start[$key],
                'task_end'         => $request->task_end[$key],
                
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }

    return redirect()->route('employee.daily_report.index')->with('success', 'Report saved successfully!');
}

}