@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0 text-dark">Assigned Query Details</h5>
            <small class="text-secondary"><a href="{{ route('employee.dashboard') }}">Home</a> / Query Details</small>
        </div>
        <!-- Blue Add Query Button -->
        <a href="{{ route('employee.queries.add') }}" class="btn text-white rounded shadow-sm px-4" style="background-color: #5c6bc0;">
            <i class="fas fa-plus-circle me-1"></i> Add Query
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-triangle me-2"></i> {{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold"><i class="fas fa-tasks"></i> Assigned Query Details</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center" style="font-size: 13px;">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>PROJECT</th>
                            <th>TITLE</th>
                            <th>QUERY DETAILS</th>
                            <th>ASSIGNED DATE</th>
                            <th>TARGET DATE</th>
                            <th>STATUS</th>
                            <th>START DATE</th>
                            <th>END DATE</th>
                            <th>DURATION</th>
                            <th>HOURS SPENT</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($queries as $q)
                        <tr>
                            <td>{{ $q->id }}</td>
                            <td>{{ $q->projectname ?? $q->productname }}</td>
                            <td>{{ $q->query_title ?? '-' }}</td>
                            <td>{{ Str::limit($q->query_details, 20) }}</td>
                            <td>{{ $q->assigned_date ? \Carbon\Carbon::parse($q->assigned_date)->format('d-m-Y') : '-' }}</td>
                            <td class="fw-bold text-danger">{{ $q->target_date ? \Carbon\Carbon::parse($q->target_date)->format('d-m-Y') : '-' }}</td>
                            <td>
                                @if($q->status == 0) <span class="badge bg-warning text-dark">New</span>
                                @elseif($q->status == 1) <span class="badge bg-info">In Progress ({{ $q->progress_percentage ?? 0 }}%)</span>
                                @else <span class="badge bg-success">Closed (100%)</span> @endif
                            </td>
                            <td>{{ $q->start_date ? \Carbon\Carbon::parse($q->start_date)->format('d-m-Y') : '-' }}</td>
                            <td>{{ $q->end_date ? \Carbon\Carbon::parse($q->end_date)->format('d-m-Y') : '-' }}</td>
                            <td class="fw-bold text-primary">
                                        @php
                                            $d1 = $q->assigned_date ? \Carbon\Carbon::parse($q->assigned_date) : null;
                                            $d2 = $q->target_date ? \Carbon\Carbon::parse($q->target_date) : null;
                                            
                                            // Calculate days - the 'false' parameter means it can return negative
                                            // We will force it to be 0 if the target is before assigned
                                            $diff = ($d1 && $d2) ? $d1->diffInDays($d2, false) : null;
                                        @endphp

                                        @if($diff !== null)
                                            {{ $diff >= 0 ? $diff : 0 }} Days
                                        @else
                                            -
                                        @endif
                                    </td>
                            <td class="fw-bold text-success">{{ $q->hours_spent ?? 0 }} Hrs</td> 
                            <td>
                                <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#updateModal{{$q->id}}">Update</button>
                            </td>
                        </tr>

                        <!-- UPDATE MODAL (REQUIRED FOR BUTTON TO WORK) -->
                        <div class="modal fade" id="updateModal{{$q->id}}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header text-white" style="background-color: #0d6efd;">
                                        <h5 class="modal-title">Update Query Progress</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('employee.queries.update', $q->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Status</label>
                                                <select name="status" class="form-select">
                                                    <option value="0" {{ $q->status == 0 ? 'selected' : '' }}>New</option>
                                                    <option value="1" {{ $q->status == 1 ? 'selected' : '' }}>In Progress</option>
                                                    <option value="2" {{ $q->status == 2 ? 'selected' : '' }}>Closed</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Actual Start Date</label>
                                                <input type="datetime-local" name="start_date" class="form-control" value="{{ $q->start_date ? \Carbon\Carbon::parse($q->start_date)->format('Y-m-d\TH:i') : '' }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Actual End Date</label>
                                                <input type="datetime-local" name="end_date" class="form-control" value="{{ $q->end_date ? \Carbon\Carbon::parse($q->end_date)->format('Y-m-d\TH:i') : '' }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Hours Spent Today</label>
                                                <input type="number" step="0.1" name="hours_spent" class="form-control" placeholder="e.g. 2.5" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Completion Percentage (%)</label>
                                                <input type="number" name="progress_percentage" class="form-control" value="{{ $q->progress_percentage }}" min="0" max="100" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Progress Remarks</label>
                                                <textarea name="remarks" class="form-control" rows="3" placeholder="What was done?"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 d-flex justify-content-center">
                                            <button type="submit" class="btn btn-success rounded-pill px-4">Update Progress</button>
                                            <button type="button" class="btn btn-light rounded-pill px-4 border" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="12" class="text-danger text-center">No tasks assigned.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection