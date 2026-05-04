@extends('layouts.app')

@section('content')

{{-- ===================== ALL CSS FIRST ===================== --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<style>
    /* 1. LOCK TABLE LAYOUT */
    #reportTable {
        font-size: 11px;
        width: 1600px !important; /* Total width for all 13 columns */
        table-layout: fixed !important; /* FORCES columns to stay the size we define */
        border-collapse: collapse !important;
    }

    /* 2. HEADER STYLING */
    #reportTable thead th {
        background-color: #f1f5f9 !important;
        color: #334155 !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        padding: 12px 8px !important;
        vertical-align: middle !important;
        text-align: center;
        border: 1px solid #e2e8f0;
        white-space: nowrap;
        overflow: hidden;
    }

    /* 3. CELL STYLING */
    #reportTable tbody td {
        padding: 10px 8px;
        vertical-align: middle;
        border: 1px solid #f1f5f9;
        text-align: center;
        word-wrap: break-word; /* Allows long text to wrap inside the fixed width */
        overflow: hidden;
    }

    /* 4. DEFINE FIXED WIDTHS FOR EVERY COLUMN */
    .w-date   { width: 95px; }
    .w-emp    { width: 115px; }
    .w-proj   { width: 140px; }
    .w-mod    { width: 90px; }
    .w-task   { width: 380px; text-align: left !important; } /* Wide for description */
    .w-status { width: 110px; }
    .w-hrs    { width: 80px; }
    .w-time   { width: 100px; }
    .w-wtype  { width: 95px; }
    .w-perm   { width: 95px; }
    .w-rem    { width: 160px; text-align: left !important; }
    .w-act    { width: 80px; }

    /* Pill Badges */
    .badge-pill-custom {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 10.5px;
        font-weight: 600;
    }
    
    /* Highlight description wrapping */
    .task-text {
        display: block;
        white-space: normal !important;
        line-height: 1.5;
    }
</style>

<div class="container-fluid bg-white p-3 rounded shadow-sm">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-normal">Daily Work Report View</h4>
            <small class="text-secondary"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a> / Daily Work Report View</small>
        </div>
        <a href="{{ route('admin.daily_report.index') }}" class="btn btn-warning px-4 py-2 rounded fw-bold shadow-sm">
            Add Daily Report <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>

    {{-- FILTER ROW --}}
    <div class="border rounded p-3 mb-4 bg-light">
        <form action="{{ route('admin.team_report.index') }}" method="GET" class="row g-2 align-items-end">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="col-md-2">
                <label class="small fw-bold">Employee</label>
                <select name="employee" class="form-select">
                    <option value="">All Employees</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->emp_name }}" {{ request('employee') == $emp->emp_name ? 'selected' : '' }}>{{ $emp->emp_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="small fw-bold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="small fw-bold">Project</label>
                <select name="project" class="form-select">
                    <option value="">All Projects</option>
                    @foreach($projects as $proj)
                        <option value="{{ $proj->projectname }}" {{ request('project') == $proj->projectname ? 'selected' : '' }}>{{ $proj->projectname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="small fw-bold">From Date</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2">
                <label class="small fw-bold">To Date</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-3 w-100">Filter</button>
                <a href="{{ route('admin.team_report.index', ['tab' => $tab]) }}" class="btn btn-secondary px-3">Reset</a>
            </div>
        </form>
    </div>

    {{-- TABS --}}
    <ul class="nav nav-tabs custom-report-tabs border-bottom-0" id="reportTabs">
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'production' ? 'active' : '' }}" href="{{ route('admin.team_report.index', ['tab' => 'production']) }}">Production Team Reports</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'leader' ? 'active' : '' }}" href="{{ route('admin.team_report.index', ['tab' => 'leader']) }}">Team Leader Reports</a>
        </li>
    </ul>

    <div class="tab-content border border-top-0 p-3 bg-white shadow-sm rounded-bottom">
        @if($tab == 'production')
        <div class="tab-pane fade show active">
            <div class="table-responsive">
                <table id="reportTable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="w-date">DATE</th>
                            <th class="w-emp">EMPLOYEE</th>
                            <th class="w-proj">PROJECT</th>
                            <th class="w-mod">MODULE</th>
                            <th class="w-task">TASK DESCRIPTION</th>
                            <th class="w-status">STATUS</th>
                            <th class="w-hrs">TARGET HRS</th>
                            <th class="w-time">START</th>
                            <th class="w-time">END</th>
                            <th class="w-wtype">WORK TYPE</th>
                            <th class="w-perm">PERMISSION</th>
                            <th class="w-rem">REMARKS</th>
                            <th class="w-act">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $row)
                        <tr>
                            <td class="fw-bold">{{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}</td>
                            <td>{{ $row->emp_name }}</td>
                            <td class="text-start fw-bold text-primary">{{ $row->project_name ?? '-' }}</td>
                            <td>{{ $row->module_name ?? '-' }}</td>
                            <td class="text-start"><span class="task-text">{!! nl2br(e($row->task_description)) !!}</span></td>   
                            <td>
                                @php $st = strtolower($row->status); @endphp
                                <span class="badge badge-pill-custom bg-{{ $st == 'completed' ? 'success' : ($st == 'pending' ? 'warning text-dark' : 'info') }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="fw-bold">{{ $row->target_hours ?? '0' }} Hrs</td>
                            <td class="text-muted small">{{ $row->task_start ? date('h:i A', strtotime($row->task_start)) : '-' }}</td>
                            <td class="text-muted small">{{ $row->task_end ? date('h:i A', strtotime($row->task_end)) : '-' }}</td>
                            <td><span class="badge bg-primary badge-pill-custom">{{ $row->work_type ?? 'Office' }}</span></td>
                            <td>{{ $row->permission ?? '-' }}</td>
                            <td class="text-start small text-muted">{{ Str::limit($row->remarks, 50) }}</td>
                            <td class="text-secondary fw-bold">-</td> 
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        {{-- Team Leader Tab --}}
        <div class="tab-pane fade show active">
            <div class="table-responsive">
                <table id="leaderTable" class="table table-bordered table-hover align-middle text-center w-100">
                    <thead class="table-light">
                        <tr><th>#</th><th>NAME</th><th>DATE</th><th>REPORT</th><th>PERMISSION</th><th>WORK TYPE</th></tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $row->emp_name }}</td>
                            <td>{{ date('d/m/Y', strtotime($row->date)) }}</td>
                            <td class="text-start">{!! nl2br(e($row->report ?? $row->task)) !!}</td>
                            <td>{{ $row->permission ?? '-' }}</td>
                            <td><span class="badge bg-{{ $row->work_type == 'Office' ? 'success' : 'info' }}">{{ $row->work_type }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    var tableId = '{{ $tab }}' === 'production' ? '#reportTable' : '#leaderTable';

    $(tableId).DataTable({
        pageLength: 10,
        ordering: false,
        autoWidth: false, // Important to prevent DataTables from overriding our CSS widths
        scrollX: true,    // Enables the horizontal bar for our 1600px width
        language: { search: "Quick Search:" }
    });
});
</script>
@endsection