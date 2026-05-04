@extends('layouts.app')

@section('content')
<!-- DataTables & FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>
    #queryTable { font-size: 12px; width: 100% !important; border-collapse: collapse; border: 1px solid #e2e8f0; }
    #queryTable thead th {
        background-color: #f8fafc !important; color: #475569 !important;
        font-weight: 700; text-transform: uppercase; font-size: 11px;
        padding: 12px 10px !important; vertical-align: middle; text-align: center;
        border: 1px solid #e2e8f0;
    }
    #queryTable tbody td { padding: 10px 8px; vertical-align: middle; border: 1px solid #f1f5f9; color: #334155; }
    
    /* Optimized for Full Content Display */
    .w-task { 
        min-width: 300px; 
        max-width: 450px; 
        white-space: normal !important; 
        word-break: break-word; 
        text-align: left !important; 
        line-height: 1.6; 
        padding: 15px 10px !important;
    }
    
    /* Modern Pill Badges */
    .badge-custom { padding: 5px 12px; border-radius: 50px; font-weight: 600; font-size: 10.5px; display: inline-block; cursor: pointer; text-decoration: none !important; }
    .status-assigned { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; cursor: default; }
    .status-progress { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .status-closed { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    
    /* Modal Styling */
    .log-item { border-left: 3px solid #0d6efd; background: #f8fafc; padding: 10px; margin-bottom: 10px; border-radius: 0 5px 5px 0; }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0 text-dark fw-bold">Query Report</h5>
            <small class="text-secondary"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a> / Query Report</small>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-secondary">All Assigned Query Details</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="queryTable" class="table table-hover align-middle m-0">
                    <thead>
                        <tr>
                            <th style="width: 40px;">S.no</th>
                            <th>PROJECT / PRODUCT</th>
                            <th>ASSIGNED TO</th>
                            <th class="w-task">QUERY / TASK DESCRIPTION</th>
                            <th>ASSIGN DATE</th>
                            <th>TARGET DATE</th>
                            <th>PRIORITY</th>
                            <th>DURATION</th>
                            <th>STATUS</th>
                            <th style="width: 100px;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($queries as $query)
                        @php
                            $start = $query->assigned_date ? \Carbon\Carbon::parse($query->assigned_date) : null;
                            $end = $query->target_date ? \Carbon\Carbon::parse($query->target_date) : null;
                            $days = ($start && $end) ? $start->diffInDays($end) : '-';
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="fw-bold">
                                <span class="text-primary">{{ $query->projectname ?? ($query->productname ?? 'N/A') }}</span>
                            </td>
                            <td class="text-center">{{ $query->assigned_to ?? 'Not Assigned' }}</td>
                            
                            {{-- UPDATED: Removed Str::limit to show full content --}}
                            <td class="w-task">
                                {!! nl2br(e($query->query_details)) !!}
                            </td>

                            <td class="text-center">{{ $query->assigned_date ? \Carbon\Carbon::parse($query->assigned_date)->format('d-m-Y') : '-' }}</td>
                            <td class="text-center">{{ $query->target_date ? \Carbon\Carbon::parse($query->target_date)->format('d-m-Y') : '-' }}</td>
                            <td class="text-center">
                                <span class="badge-custom {{ $query->priority == 'High' ? 'bg-danger text-white' : ($query->priority == 'Medium' ? 'bg-warning text-dark' : 'bg-info text-dark') }}">
                                    {{ $query->priority }}
                                </span>
                            </td>
                            <td class="text-center fw-bold text-secondary">{{ $days != '-' ? $days . ' Days' : '-' }}</td>
                            
                            <td class="text-center">
                                @if($query->status == 0) 
                                    <span class="badge-custom status-assigned">Assigned</span>
                                @else
                                    @php $statusClass = ($query->status == 1) ? 'status-progress' : 'status-closed'; @endphp
                                    <a href="javascript:void(0)" class="badge-custom {{ $statusClass }}" data-bs-toggle="modal" data-bs-target="#historyModal{{ $query->id }}">
                                        {{ $query->status == 1 ? 'In Progress' : 'Closed' }} ({{ $query->progress_percentage ?? 0 }}%)
                                    </a>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.queries.edit', $query->id) }}" class="btn btn-sm btn-outline-primary border-0"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.queries.destroy', $query->id) }}" method="POST" onsubmit="return confirm('Delete?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- PROGRESS HISTORY MODAL -->
                        <div class="modal fade" id="historyModal{{ $query->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white py-2">
                                        <h6 class="modal-title">Track Process - {{ $query->projectname ?? $query->productname }}</h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $logs = \DB::table('tbl_query_logs')->where('query_id', $query->id)->orderBy('created_at', 'desc')->get();
                                        @endphp
                                        
                                        @forelse($logs as $log)
                                            <div class="log-item shadow-sm border mb-3">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="badge bg-primary">{{ $log->percentage }}% Completed</span>
                                                    <small class="text-muted fw-bold"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}</small>
                                                </div>
                                                <div class="small">
                                                    <div class="mb-1 text-dark"><strong>Hours Spent:</strong> <span class="text-success fw-bold">{{ $log->hours_spent }} Hrs</span></div>
                                                    <div class="text-secondary"><strong>Remarks:</strong> {{ $log->remarks ?? 'No remarks provided.' }}</div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-4 text-muted">
                                                <p>No progress updates submitted yet.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    <div class="modal-footer py-1">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#queryTable').DataTable({
        "pageLength": 10,
        "autoWidth": false,
        "language": { "search": "Quick Search:" }
    });
});
</script>
@endsection