@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-normal">Add Project Based Queries</h4>
            <small class="text-secondary"><a href="{{ route('admin.dashboard') }}">Home</a> / Add Query</small>
        </div>
        <a href="{{ route('admin.queries.list') }}" class="btn btn-warning fw-bold px-4 rounded shadow-sm">
            View All Queries <i class="fas fa-list ms-1"></i>
        </a>
    </div>

    <div class="bg-white p-4 rounded shadow-sm border">
        <form action="{{ route('admin.queries.store') }}" method="POST">
            @csrf
            
            <!-- FIXED COMMON SECTION -->
            <div class="row mb-4 p-3 bg-light rounded border shadow-sm">
                <div class="col-md-4">
                    <label class="small fw-bold text-uppercase text-primary">Select Project *</label>
                    <select name="common_project" class="form-select border-primary" required>
                        <option value="">-- Select Project --</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->projectname }}">{{ $p->projectname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-uppercase">Entry Date (System Date)</label>
                    <input type="date" class="form-control bg-white" value="{{ date('Y-m-d') }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-uppercase text-primary">Status</label>
                    <select name="common_status" class="form-select border-primary">
                        <option value="0">New</option>
                        <option value="1">In Progress</option>
                        <option value="2">Closed</option>
                    </select>
                </div>
            </div>

            <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-tasks"></i> Task Assignments</h6>
            
            <div id="task-wrapper">
                <div class="card mb-3 task-row border-primary shadow-sm" style="border-left: 5px solid #0d6efd !important;">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="small text-muted fw-bold">Assign To Employee </label>
                                <select name="assigned_to[]" class="form-select">
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

                            <!-- ASSIGNED DATE (Added back here) -->
                            <div class="col-md-3">
                                <label class="small text-muted fw-bold">Assigned Date </label>
                                <input type="date" name="assigned_date[]" class="form-control"  >
                            </div>

                            <div class="col-md-3">
                                <label class="small text-muted fw-bold">Target Date (Deadline) </label>
                                <input type="date" name="target_date[]" class="form-control">
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

            <button type="button" class="btn btn-primary btn-sm px-4 rounded-pill mt-2" id="add-task-btn">
                <i class="fas fa-plus-circle me-1"></i> Add Query
            </button>

            <div class="text-center mt-5 border-top pt-4">
                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill" style="background-color: #5c6bc0; border: none;">
                    Save All Queries to Grid
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $("#add-task-btn").click(function() {
        let newRow = $(".task-row:first").clone();
        newRow.find("input, textarea").val(""); 
        newRow.find("select").val(""); 
        newRow.find('input[name="assigned_date[]"]').val('{{ date("Y-m-d") }}'); // Set default date for new row
        newRow.find(".remove-task").removeClass('d-none');
        $("#task-wrapper").append(newRow);
    });

    $(document).on("click", ".remove-task", function() {
        $(this).closest(".task-row").remove();
    });
});
</script>
@endsection