@extends('layouts.app')

@section('content')
<!-- CSS for DataTables & Print Formatting -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    /* Styling for the Actual Work link */
    .task-link { color: #198754; font-weight: bold; text-decoration: none; cursor: pointer; }
    .task-link:hover { text-decoration: underline; color: #157347; }
    
    /* Formatting for Print/PDF */
    @media print {
        .no-print, .btn, .card-body form, .dataTables_wrapper .row:first-child { display: none !important; }
        .card { border: none !important; shadow: none !important; }
        .table { font-size: 10px !important; width: 100% !important; }
        body { background: white !important; }
        .container-fluid { padding: 0 !important; }
    }
</style>

<div class="container-fluid py-3">
    
    <!-- HEADER SECTION -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0 text-dark text-uppercase fw-bold">Attendance & Task Report</h5>
            <small class="text-secondary">Logged in: <strong>{{ $user->emp_name }}</strong> | {{ date('d M Y', strtotime($fromDate)) }} to {{ date('d M Y', strtotime($toDate)) }}</small>
        </div>
        <div class="no-print">
            <a href="{{ route('admin.attendance.export', request()->all()) }}" class="btn btn-success btn-sm px-3 shadow-sm">
                <i class="fas fa-file-excel me-1"></i> Excel
            </a>
            <button class="btn btn-danger btn-sm px-3 shadow-sm" onclick="window.print()">
                <i class="fas fa-file-pdf me-1"></i> PDF
            </button>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="card shadow-sm border-0 mb-4 no-print">
        <div class="card-body">
            <form action="{{ route('admin.attendance.report') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">From Date</label>
                    <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $fromDate }}">
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">To Date</label>
                    <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $toDate }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Filter Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ATTENDANCE TABLE -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center mb-0" style="font-size: 11px;">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>#</th>
                            <th class="text-start">EMPLOYEE NAME</th>
                            <th>TOTAL</th>
                            <th>SUN</th>
                            <th>HOL</th>
                            <th>WORK DAYS</th>
                            <th>LEAVE</th>
                            <th>WFH</th>
                            <th>PERM</th>
                            <th>OT</th>
                            <th>ACTUAL WORK</th>
                            <th>PAYABLE DAYS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-start fw-bold text-dark">{{ $row['name'] }}</td>
                            <td>{{ $row['total_days'] }}</td>
                            <td>{{ $row['sundays'] }}</td>
                            <td>{{ $row['holidays'] }}</td>
                            <td>{{ $row['working_days'] }}</td>
                            <td><span class="badge bg-danger rounded-pill">{{ $row['leave'] }}</span></td>
                            <td><span class="badge bg-info px-2">{{ $row['wfh'] }}</span></td>
                            <td><span class="badge bg-dark px-2">{{ $row['permission'] }}</span></td>
                            <td><span class="badge bg-success px-2">{{ $row['ot'] }}</span></td>

                            {{-- ACTUAL WORK COLUMN --}}
                            <td>
                                @if($row['actual'] > 0)
                                    <a href="javascript:void(0)" 
                                       class="task-link" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#taskLogModal{{ $row['id'] }}">
                                        {{ $row['actual'] }} Days
                                    </a>
                                @else
                                    <span class="text-muted">0</span>
                                @endif
                            </td>

                            {{-- PAYABLE DAYS --}}
                            <td class="fw-bold text-primary" style="font-size: 13px;">{{ $row['payable'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> <!-- Main container closed correctly -->

<!-- TASK LOG MODALS (Placed outside the table to prevent UI breaking) -->
@foreach($reportData as $row)
    @if($row['actual'] > 0)
    <div class="modal fade" id="taskLogModal{{ $row['id'] }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title"><i class="fas fa-list-check me-2"></i>Task Submission Records: {{ $row['name'] }}</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0" style="font-size: 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 35%;">Submission Date & Time</th>
                                    <th>Task / Query Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($row['logs'] as $log)
                                <tr>
                                    <td class="ps-3 fw-bold text-muted">
                                        {{ date('d-m-Y', strtotime($log->created_at)) }}<br>
                                        <small class="fw-normal">{{ date('h:i A', strtotime($log->created_at)) }}</small>
                                    </td>
                                    <td>{{ $log->query_title }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light py-1">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

@endsection