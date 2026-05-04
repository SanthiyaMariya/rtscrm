@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 text-dark">Daily Work Report</h4>
            <small class="text-secondary"><a href="{{ route('admin.dashboard') }}">Home</a> / Daily Work Report</small>
        </div>
        {{-- The Yellow Button from your image --}}
       <a href="{{ route('admin.team_report.index') }}" class="btn btn-warning btn-sm fw-bold px-3 shadow-sm" style="color: #000; background-color: #ffc107;">
    View Team Report &raquo;
</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 shadow-sm border-0">{{ session('success') }}</div>
    @endif

    <!-- INPUT FORM SECTION (TOP) -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold">Daily Update ({{ $user->designation }})</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.daily_report.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Date</label>
                        <input type="text" class="form-control bg-light" value="{{ date('d/m/Y') }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Employee Name</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->emp_name }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Work Type</label>
                        <select name="work_type" class="form-select" required>
                            <option value="Office">Office</option>
                            <option value="WFH">WFH</option>
                            <option value="Onsite">Onsite</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small fw-bold">Remarks / Daily Update</label>
                        <textarea name="report" class="form-control" rows="4" placeholder="Enter your daily update..." required></textarea>
                    </div>
                    <div class="col-12 text-center mt-3">
                        {{-- The Green Button from your image --}}
                        <button type="submit" class="btn btn-success px-5 rounded-pill shadow-sm fw-bold" style="background-color: #48c9b0; border: none;">Submit Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MY REPORT GRID SECTION (BOTTOM) -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold">My Report</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center mb-0" style="font-size: 12px;">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>NAME</th>
                            <th>DATE</th>
                            <th class="text-start">REPORT</th>
                            <th>PERMISSION</th>
                            <th>WORK TYPE</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                  <tbody>
    @foreach($myReports as $index => $report)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $report->emp_name }}</td>
        <td>{{ date('d/m/Y', strtotime($report->date)) }}</td>
        <td class="text-start">{!! nl2br(e($report->report)) !!}</td>
        <td>{{ $report->permission }}</td>
        <td><span class="badge bg-primary">{{ $report->work_type }}</span></td>
        <td>
            <div class="d-flex justify-content-center gap-1">
                <!-- EDIT BUTTON -->
                <button class="btn btn-sm btn-outline-primary border-0" data-bs-toggle="modal" data-bs-target="#editModal{{ $report->id }}">
                    <i class="fas fa-edit"></i>
                </button>

                <!-- DELETE BUTTON (Fixed Route Name) -->
                <form action="{{ route('admin.daily_report.delete', $report->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </td>
    </tr>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="editModal{{ $report->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title">Edit Update</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.daily_report.update', $report->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Work Type</label>
                            <select name="work_type" class="form-select">
                                <option value="Office" {{ $report->work_type == 'Office' ? 'selected' : '' }}>Office</option>
                                <option value="WFH" {{ $report->work_type == 'WFH' ? 'selected' : '' }}>WFH</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Update Content</label>
                            <textarea name="report" class="form-control" rows="5" required>{{ $report->report }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-sm px-4">Update Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</tbody>
 </div>
        </div>
    </div>
</div>
@endsection