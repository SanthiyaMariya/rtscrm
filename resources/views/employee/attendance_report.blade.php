@extends('layouts.app')

@section('content')
<!-- DataTables CSS for the styling -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .card-header { background-color: #fff; border-bottom: 1px solid #eee; padding: 15px; }
    .breadcrumb { background: transparent; padding: 0; font-size: 13px; }
    .table thead th { 
        background-color: #f8f9fa; 
        color: #333; 
        font-size: 11px; 
        text-transform: uppercase;
        vertical-align: middle;
        border-bottom: 2px solid #dee2e6;
    }
    .table tbody td { font-size: 12px; color: #666; vertical-align: middle; }
    .badge-count {
        background-color: #777;
        color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: bold;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.2em 0.5em; }
    .btn-filter { background-color: #007bff; color: white; }
    .btn-reset { background-color: #6c757d; color: white; }
</style>

<div class="container-fluid py-3">
    <!-- Page Title & Breadcrumb -->
    <h4 class="mb-1 text-dark fw-normal">Attendance Report - {{ date('d M Y', strtotime($fromDate)) }} to {{ date('d M Y', strtotime($toDate)) }}</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#" class="text-primary text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active text-secondary" aria-current="page">Attendance Report</li>
        </ol>
    </nav>

    <!-- Main Card -->
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header d-flex align-items-center">
            <i class="far fa-list-alt me-2 text-secondary"></i>
            <h6 class="mb-0 text-dark">Attendance Report</h6>
        </div>
        <div class="card-body p-4">
            
            <!-- Filter Form -->
            <form action="{{ route('employee.attendance_report') }}" method="GET" class="row g-3 align-items-end mb-4">
                <div class="col-md-3">
                    <label class="small text-muted mb-1">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                </div>
                <div class="col-md-3">
                    <label class="small text-muted mb-1">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-filter px-4">Filter</button>
                    <a href="{{ route('employee.attendance_report') }}" class="btn btn-reset px-4">Reset</a>
                </div>
            </form>

            <hr class="text-muted opacity-25 mb-4">

            <!-- Data Table -->
            <div class="table-responsive">
                <table id="attendanceTable" class="table table-bordered table-hover w-100 text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-start">Employee Name</th>
                            <th>Total Days</th>
                            <th>Sundays</th>
                            <th>Holidays</th>
                            <th>Working Days</th>
                            <th>Leave</th>
                            <th>WFH</th>
                            <th>Permission</th>
                            <th>OT</th>
                            <th>Actual Working Days</th>
                            <th>Total Payable Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-start">{{ $row['name'] }}</td>
                            <td>{{ $row['total_days'] }}</td>
                            <td>{{ $row['sundays'] }}</td>
                            <td>{{ $row['holidays'] }}</td>
                            <td>{{ $row['working_days'] }}</td>
                            <td><span class="badge-count">{{ $row['leave'] }}</span></td>
                            <td><span class="badge-count">{{ $row['wfh'] }}</span></td>
                            <td><span class="badge-count">{{ $row['permission'] }}</span></td>
                            <td><span class="badge-count">{{ $row['ot'] }}</span></td>
                            
                            {{-- Actual Working Days with Modal Trigger --}}
                            <td>
                                @if($row['actual'] > 0)
                                    <a href="javascript:void(0)" class="text-primary fw-bold text-decoration-none" 
                                       data-bs-toggle="modal" data-bs-target="#taskLogModal{{ $row['id'] }}">
                                        {{ $row['actual'] }}
                                    </a>
                                @else
                                    0
                                @endif
                            </td>

                            <td class="fw-bold">{{ $row['payable'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Task Details (Optional feature from previous request) -->
@foreach($reportData as $row)
    @if($row['actual'] > 0)
    <div class="modal fade" id="taskLogModal{{ $row['id'] }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h6 class="modal-title">Task Submission Records: {{ $row['name'] }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <table class="table table-sm mb-0">
                        <thead><tr><th class="ps-3">Date</th><th>Task Details</th></tr></thead>
                        <tbody>
                            @foreach($row['logs'] as $log)
                            <tr>
                                <td class="ps-3">{{ date('d-m-Y', strtotime($log->created_at)) }}</td>
                                <td>{{ $log->query_title }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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

<script>
    $(document).ready(function() {
        $('#attendanceTable').DataTable({
            "pageLength": 10,
            "ordering": true,
            "info": true,
            "searching": true,
            "language": {
                "search": "Search:",
                "paginate": {
                    "previous": "Previous",
                    "next": "Next"
                }
            }
        });
    });
</script>
@endsection