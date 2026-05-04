@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 text-dark">Edit Query</h5>
        <small class="text-secondary"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a> / Query Report</small>
    </div>
    <a href="{{ route('admin.queries.list') }}" class="btn text-white rounded-pill px-4" style="background-color: #5c6bc0;">
        <i class="fas fa-eye"></i> View Query
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom">
        <h6 class="mb-0 fw-bold">Update / Edit Assigned Query</h6>
    </div>
    <div class="card-body p-4">
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.queries.update', $query->id) }}" method="POST" id="editQueryForm">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Select Project <span class="text-danger">*</span></label>
                    <select name="projectname" id="projectSelect" class="form-select border-info">
                        <option value="">-- Select Project --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->projectname }}" {{ $query->projectname == $project->projectname ? 'selected' : '' }}>
                                {{ $project->projectname }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Select Product <span class="text-danger">*</span></label>
                    <select name="productname" id="productSelect" class="form-select border-info">
                        <option value="">-- Select Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->productname }}" {{ $query->productname == $product->productname ? 'selected' : '' }}>
                                {{ $product->productname }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Assign To Employee</label>
                    <select name="assigned_to" class="form-select">
                        <option value="">-- Select Employee --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->emp_name }}" {{ $query->assigned_to == $employee->emp_name ? 'selected' : '' }}>
                                {{ $employee->emp_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="Low" {{ $query->priority == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ $query->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High" {{ $query->priority == 'High' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Assigned Date</label>
                    {{-- REMOVED THE VALUE ATTRIBUTE TO MAKE IT EMPTY (dd-mm-yyyy) --}}
                    <input type="date" name="assigned_date" class="form-control"    value="{{ $query->assigned_date ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-bold">Target Date (Deadline)</label>
                    {{-- Showing empty by default if NULL --}}
                    <input type="date" name="target_date" class="form-control" value="{{ $query->target_date }}" >
                </div>

                <div class="col-md-9">
                    <label class="form-label small fw-bold">Query Details</label>
                    <textarea name="query_details" class="form-control" rows="3">{{ $query->query_details }}</textarea>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="0" {{ $query->status == 0 ? 'selected' : '' }}>New</option>
                        <option value="1" {{ $query->status == 1 ? 'selected' : '' }}>In Progress</option>
                        <option value="2" {{ $query->status == 2 ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5 rounded-pill" style="background-color: #007bff; border: none;">
                        Update Query
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('editQueryForm').onsubmit = function(e) {
        var project = document.getElementById('projectSelect').value;
        var product = document.getElementById('productSelect').value;
        
        if (project === "" && product === "") {
            alert("Please select either a Project or a Product before updating.");
            e.preventDefault();
            return false;
        }
    };
</script>
@endsection