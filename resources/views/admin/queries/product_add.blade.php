@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-normal">Add Product Based Queries</h4>
            <small class="text-secondary"><a href="{{ route('admin.dashboard') }}">Home</a> / Add Query</small>
        </div>
        <a href="{{ route('admin.queries.list') }}" class="btn btn-warning fw-bold px-4 rounded shadow-sm">
            View All Queries <i class="fas fa-list ms-1"></i>
        </a>
    </div>

    <div class="bg-white p-4 rounded shadow-sm border">
        <form action="{{ route('admin.queries.store') }}" method="POST">
            @csrf
            
            <!-- FIXED COMMON SECTION (Top Row) -->
            <div class="row mb-4 p-3 bg-light rounded border shadow-sm">
                <div class="col-md-4">
                    <label class="small fw-bold text-uppercase text-success">Select Product *</label>
                    <select name="common_product" class="form-select border-success" required>
                        <option value="">-- Select Product --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->productname }}">{{ $p->productname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-uppercase">Entry Date (System Date)</label>
                    <input type="date" name="common_date" class="form-control bg-white" value="{{ date('Y-m-d') }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-uppercase text-success">Status</label>
                    <select name="common_status" class="form-select border-success">
                        <option value="0">New</option>
                        <option value="1">In Progress</option>
                        <option value="2">Closed</option>
                    </select>
                </div>
            </div>

            <h6 class="fw-bold mb-3 text-success"><i class="fas fa-tasks"></i> Task Assignments</h6>
            
            <!-- DYNAMIC TASK SECTION -->
            <div id="task-wrapper">
                <div class="card mb-3 task-row border-success shadow-sm" style="border-left: 5px solid #198754 !important;">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="small text-muted fw-bold">Assign To Employee </label>
                                <select name="assigned_to[]" class="form-select" >
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $e)
                                        <option value="{{ $e->emp_name }}">{{ $e->emp_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="small text-muted fw-bold">Priority</label>
                                <select name="priority[]" class="form-select">
                                    <option value="Low">Low</option>
                                    <option value="Medium" selected>Medium</option>
                                    <option value="High">High</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="small text-muted fw-bold">Assigned Date </label>
                                <input type="date" name="assigned_date[]" class="form-control" >
                            </div>

                            <div class="col-md-3">
                                <label class="small text-muted fw-bold">Target Date (Deadline) </label>
                                <input type="date" name="target_date[]" class="form-control" >
                            </div>

                            <div class="col-md-1 d-flex align-items-end justify-content-center">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-task d-none">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <div class="col-md-12">
                                <label class="small text-muted fw-bold">Query Details / Task Description</label>
                                <textarea name="query_details[]" class="form-control" rows="2" placeholder="Describe the task details..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Task Button -->
            <button type="button" class="btn btn-primary btn-sm px-4 rounded-pill mt-2" id="add-task-btn">
                <i class="fas fa-plus-circle me-1"></i> Add Query
            </button>

            <!-- Final Submit Button -->
            <div class="text-center mt-5 border-top pt-4">
                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill" style="background-color: #5c6bc0; border: none;">
                    Save All Queries to Grid
                </button>
            </div>
        </form>
    </div>
</div>

{{-- jQuery for cloning rows --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $("#add-task-btn").click(function() {
        let newRow = $(".task-row:first").clone();
        newRow.find("input, textarea").val(""); 
        newRow.find("select").val(""); 
        
        // REMOVED: The line that was setting assigned_date to current date
        
        newRow.find(".remove-task").removeClass('d-none');
        $("#task-wrapper").append(newRow);
    });
    // ... rest of code
});
</script>

@endsection