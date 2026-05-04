@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold" style="color: #2c3e50;">Welcome, {{ Auth::user()->emp_name }}</h2>
    <p class="text-muted mb-0">Designation: <strong>{{ Auth::user()->designation }}</strong></p>
    <p class="text-muted">Department: <strong>{{ Auth::user()->department ?? 'Production' }}</strong></p>
</div>

<!-- Stat Cards Row -->
<div class="row g-3 mb-4 text-white text-center">
    <div class="col-md-2">
        <div class="card bg-primary h-100 border-0 py-3 shadow-sm">
            <i class="far fa-calendar-alt fs-2 mb-2"></i>
            <h6>Today Report</h6>
            <h3>0</h3>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success h-100 border-0 py-3 shadow-sm">
            <i class="fas fa-calendar-check fs-2 mb-2"></i>
            <h6>Monthly Reports</h6>
            <h3>5</h3>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger h-100 border-0 py-3 shadow-sm">
            <i class="fas fa-file-excel fs-2 mb-2"></i>
            <h6>Leaves</h6>
            <h3>5</h3>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning h-100 border-0 py-3 shadow-sm text-dark">
            <i class="fas fa-home fs-2 mb-2"></i>
            <h6>WFH</h6>
            <h3>47</h3>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card h-100 border-0 py-3 shadow-sm" style="background-color: #6f42c1;">
            <i class="fas fa-clock fs-2 mb-2"></i>
            <h6>OT</h6>
            <h3>0</h3>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card h-100 border-0 py-3 shadow-sm" style="background-color: #20c997;">
            <i class="fas fa-key fs-2 mb-2"></i>
            <h6>Permission</h6>
            <h3>0</h3>
        </div>
    </div>
</div>

<!-- Recent Reports Table -->
<h5 class="mb-3 text-secondary">Recent Reports (Last 3 Days)</h5>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-striped mb-0 text-center">
            <thead style="background-color: #f1f5f9;">
                <tr>
                    <th class="py-3 text-secondary">DATE</th>
                    <th class="py-3 text-secondary">STATUS</th>
                    <th class="py-3 text-secondary">NOTES</th>
                </tr>
            </thead>
            <tbody>
                <!-- Currently Empty as per your screenshot -->
                <tr>
                    <td colspan="3" class="py-4 text-muted">No recent reports found.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection