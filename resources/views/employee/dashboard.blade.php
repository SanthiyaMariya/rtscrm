@extends('layouts.app') {{-- Change 'layouts.app' to your actual layout file name if different --}}

@section('content')
<!-- Add a wrapper to ensure it stays to the right of the sidebar -->
<div class="container-fluid py-4">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="font-weight-bold">Welcome, {{ $user->emp_name ?? 'User' }}</h2>
            <p class="mb-0 text-muted">Designation: <strong>{{ $user->designation ?? 'Junior Developer' }}</strong></p>
            <p class="text-muted">Department: <strong>{{ $user->department ?? 'Production' }}</strong></p>
        </div>
    </div>

    <!-- Stat Cards Row -->
    <div class="row text-center mb-4">
        
        <!-- Today Report -->
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card text-white h-100" style="background-color: #4e73df; border-radius: 8px;">
                <div class="card-body p-4">
                    <i class="far fa-calendar-alt fa-2x mb-2"></i>
                    <h6 class="card-title">Today Report</h6>
                    <h3 class="mb-0">{{ $data['todayCount'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Monthly Reports -->
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card text-white h-100" style="background-color: #1cc88a; border-radius: 8px;">
                <div class="card-body p-4">
                    <i class="fas fa-calendar-week fa-2x mb-2"></i>
                    <h6 class="card-title">Monthly Reports</h6>
                    <h3 class="mb-0">{{ $data['monthlyCount'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Leaves -->
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card text-white h-100" style="background-color: #e74a3b; border-radius: 8px;">
                <div class="card-body p-4">
                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                    <h6 class="card-title">Leaves</h6>
                    <h3 class="mb-0">{{ $data['leaveCount'] }}</h3>
                </div>
            </div>
        </div>

        <!-- WFH -->
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card text-white h-100" style="background-color: #f6c23e; border-radius: 8px;">
                <div class="card-body p-4">
                    <i class="fas fa-home fa-2x mb-2"></i>
                    <h6 class="card-title">WFH</h6>
                    <h3 class="mb-0">{{ $data['wfhCount'] }}</h3>
                </div>
            </div>
        </div>

        <!-- OT -->
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card text-white h-100" style="background-color: #6f42c1; border-radius: 8px;">
                <div class="card-body p-4">
                    <i class="far fa-clock fa-2x mb-2"></i>
                    <h6 class="card-title">OT</h6>
                    <h3 class="mb-0">{{ $data['otCount'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Permission -->
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card text-white h-100" style="background-color: #36b9cc; border-radius: 8px;">
                <div class="card-body p-4">
                    <i class="fas fa-key fa-2x mb-2"></i>
                    <h6 class="card-title">Permission</h6>
                    <h3 class="mb-0">{{ $data['permissionCount'] }}</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Reports Table -->
    <!-- Recent Reports Table -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h5 class="mb-0 text-muted">Recent Reports (Latest Tasks)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-borderless table-striped text-center">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="text-start">DATE</th>
                        <th>PROJECT</th>
                        <th>STATUS</th>
                        <th class="text-start">TASK DESCRIPTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReports as $report)
                        <tr>
                            <td class="text-start">{{ \Carbon\Carbon::parse($report->date)->format('d M Y') }}</td>
                            <td class="fw-bold">{{ $report->project_name }}</td>
                            <td>
                                <span class="badge {{ $report->status == 'Completed' ? 'bg-success' : 'bg-info' }}">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="text-start">{{ Str::limit($report->task_description, 60) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-muted">No recent reports found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
@endsection