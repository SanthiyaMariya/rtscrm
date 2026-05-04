@extends('layouts.app')

@section('content')
<!-- Add DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-dark fw-bold">Team Leave / Half Day</h5>
        <small><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a> / Half Day</small>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- FORM SIDE (Left) -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 fw-bold">
                    <i class="fas fa-edit me-2 text-primary"></i> Apply Leave / Half Day
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.leave.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Employee Name</label>
                                <select name="employee_name" class="form-select" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->emp_name }}">{{ $emp->emp_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Leave Date</label>
                                <input type="date" name="leave_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Leave Type</label>
                                <select name="leave_type" class="form-select" required>
                                    <option value="">Select</option>
                                    <option value="Full Day">Full Day</option>
                                    <option value="Half Day">Half Day</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Details</label>
                                <textarea name="details" class="form-control" rows="1" placeholder="Personal / Medical..."></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 mt-2" style="background-color:#5c6bc0; border:none;">Apply</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- LIST SIDE (Right) -->
        <div class="col-md-7">
            <!-- Applied Leave List -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 fw-bold">
                    <i class="fas fa-list me-2 text-primary"></i> Applied Leave List
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-bordered table-hover align-middle text-center" style="font-size: 13px;">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>S.NO</th>
                                    <th>EMPLOYEE</th>
                                    <th>DATE</th>
                                    <th>TYPE</th>
                                    <th>DETAILS</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($leaves) && count($leaves) > 0)
                                    @foreach($leaves as $index => $l)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-bold">{{ $l->employee_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($l->leave_date)->format('d-m-Y') }}</td>
                                        <td>
                                            <span class="badge {{ $l->leave_type == 'Half Day' ? 'bg-warning text-dark' : 'bg-danger' }}">
                                                {{ $l->leave_type }}
                                            </span>
                                        </td>
                                        <td>{{ $l->details }}</td>
                                        <td>
                                            <form action="{{ route('admin.leave.delete', $l->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Delete record?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="6">No leaves applied yet.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Add Master Holiday Form -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-umbrella-beach me-2"></i> Add Master Holiday (Govt/Local)</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.holiday.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Holiday Name</label>
                            <input type="text" name="holiday_name" class="form-control" placeholder="e.g. Diwali / Sunday" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Holiday Date</label>
                                <input type="date" name="holiday_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Type</label>
                                <select name="holiday_type" class="form-select" required>
                                    <option value="Government">Government</option>
                                    <option value="Local">Local</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 rounded-pill py-2">Save to Master List</button>
                    </form>
                </div>
            </div>
        </div> <!-- End col-md-7 -->
    </div> <!-- End Row -->

    {{-- NEW SECTION: TEAM LEAVE & HOLIDAY MASTER REPORT (Full Width) --}}
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-calendar-check me-2 text-primary"></i> Team Leave & Holiday Master List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="masterLeaveTable" class="table table-bordered table-hover align-middle text-center" style="font-size: 13px; width:100%;">
                    <thead class="table-light">
                        <tr>
                            <th>S.NO</th>
                            <th class="text-start">EMPLOYEE / HOLIDAY NAME</th>
                            <th>DATE</th>
                            <th>TYPE</th>
                            <th>DETAILS / REMARKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- 1. Loop through Master Holidays --}}
                        @if(isset($holidays) && count($holidays) > 0)
                            @foreach($holidays as $h)
                            <tr style="background-color: #fff8e1;">
                                <td><i class="fas fa-star text-warning"></i></td>
                                <td class="fw-bold text-start">{{ $h->holiday_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($h->holiday_date)->format('d-m-Y') }}</td>
                                <td><span class="badge bg-dark">{{ $h->holiday_type ?? 'Government' }}</span></td>
                                <td>Master Holiday (Office Closed)</td>
                            </tr>
                            @endforeach
                        @endif

                        {{-- 2. Loop through Employee Leaves --}}
                        @if(isset($leaves) && count($leaves) > 0)
                            @foreach($leaves as $index => $l)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-start">{{ $l->employee_name ?? 'Unknown' }}</td>
                                <td>{{ \Carbon\Carbon::parse($l->leave_date)->format('d-m-Y') }}</td>
                                <td>
                                    @php $lType = $l->leave_type ?? 'Full Day'; @endphp
                                    <span class="badge {{ $lType == 'Half Day' ? 'bg-warning text-dark' : 'bg-danger' }}">
                                        {{ $lType }}
                                    </span>
                                </td>
                                <td>{{ $l->details ?? '-' }}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- DataTables JavaScript -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize the Master Leave Table with DataTables (Search, Pagination, Show Entries)
    $('#masterLeaveTable').DataTable({
        "pageLength": 10,
        "ordering": false, // Disable default sorting to keep Holidays at the top
        "autoWidth": false,
        "language": {
            "search": "Search Master List:"
        }
    });
});
</script>
@endsection