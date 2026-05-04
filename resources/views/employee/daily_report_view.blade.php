@extends('layouts.app')

@section('content')
<!-- DataTables & FontAwesome -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    .view-title { font-weight: 400; color: #333; font-size: 24px; }
    .my-reports-tab {
        background-color: #00d2ff; color: white; border: none; padding: 10px 30px;
        font-weight: bold; border-radius: 5px 5px 0 0; display: inline-block; font-size: 14px;
    }
    #reportTable { font-size: 12px; border-collapse: collapse !important; width: 100% !important; }
    #reportTable thead th {
        background-color: #f8fafc !important; color: #475569 !important; font-weight: 700;
        text-transform: uppercase; border: 1px solid #cbd5e1 !important; vertical-align: middle;
        text-align: center; padding: 12px 8px;
    }
    #reportTable tbody td { border: 1px solid #e2e8f0 !important; vertical-align: middle; padding: 10px 8px; }
    .col-task { min-width: 300px; max-width: 450px; white-space: normal !important; text-align: left !important; line-height: 1.6; }
    .badge-pill { padding: 5px 12px; border-radius: 50px; font-weight: 600; font-size: 10px; }
    .row-query { background-color: #f0f9ff; }
</style>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="view-title mb-1">Daily Work Report View</h4>
            <small class="text-secondary"><a href="{{ route('employee.dashboard') }}" class="text-decoration-none">Home</a> / Daily Work Report View</small>
        </div>
        <a href="{{ route('employee.daily_report.create') }}" class="btn btn-warning fw-bold px-4 rounded shadow-sm" style="background-color: #ffc107; border:none; color: #000;">
            Add Daily Report <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-3 pb-0">
            <div class="my-reports-tab">My Reports</div>
        </div>
        <div class="card-body border-top p-4">
            <div class="table-responsive">
                <table id="reportTable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>EMPLOYEE</th>
                            <th>PROJECT</th>
                            <th>MODULE</th>
                            <th class="col-task">TASK</th>
                            <th>STATUS</th>
                            <th>TARGET HRS</th>
                            <th>START TIME</th>
                            <th>END TIME</th>
                            <th>WORK TYPE</th>
                            <th>PERMISSION</th>
                            <th>REMARKS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $row)
                        <tr class="{{ $row->source == 'Query' ? 'row-query' : '' }}">
                            <td class="text-center">{{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}</td>
                            <td>{{ auth()->user()->emp_name }}</td>
                            <td class="fw-bold">
                                {{ $row->project_name }}
                                @if($row->source == 'Query')
                                    <br><span class="badge bg-primary" style="font-size: 8px;">ASSIGNED QUERY</span>
                                @endif
                            </td>
                            <td>{{ $row->module_name ?? '-' }}</td>
                            <td class="col-task">{!! nl2br(e($row->task_description)) !!}</td>
                            <td class="text-center">
                                @php $st = strtolower($row->status); @endphp
                                <span class="badge badge-pill bg-{{ $st == 'completed' ? 'success' : ($st == 'pending' ? 'warning text-dark' : 'info') }}">{{ $row->status }}</span>
                            </td>
                            <td class="text-center fw-bold">{{ $row->target_hours }}</td>
                            <td class="text-center small text-muted">{{ $row->task_start ? date('d-m-Y H:i', strtotime($row->task_start)) : '-' }}</td>
                            <td class="text-center small text-muted">{{ $row->task_end ? date('d-m-Y H:i', strtotime($row->task_end)) : '-' }}</td>
                            <td class="text-center"><span class="badge bg-info badge-pill text-white">{{ $row->work_type }}</span></td>
                            <td class="text-center">{{ $row->permission ?? '-' }}</td>
                            <td><small class="text-muted">{{ Str::limit($row->remarks, 30) }}</small></td>
                            <td class="text-center">
                                @if($row->source == 'Report')
                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="#" class="btn btn-sm btn-outline-primary px-2 border-0" title="Edit"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-outline-danger px-2 border-0" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </div>
                                @else
                                    <a href="{{ route('employee.queries') }}" class="text-primary small fw-bold text-decoration-none">Update Query</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#reportTable').DataTable({ 
            "pageLength": 10, 
            "autoWidth": false,
            "ordering": false // Keep the chronological order from controller
        });
    });
</script>
@endsection