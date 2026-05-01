@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-dark fw-normal">Daily Work Report</h4>
        <a href="{{ route('employee.daily_report.index') }}" class="btn btn-warning fw-bold px-4 rounded shadow-sm" style="background-color: #ffc107; border:none; color: #000;">
            View Daily Report <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-edit me-1"></i> Submit New Report</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('employee.daily_report.store') }}" method="POST" id="reportForm">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="small fw-bold">DATE</label>
                        <input type="date" name="report_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold">EMPLOYEE NAME</label>
                        <input type="text" class="form-control bg-light" value="{{ auth()->user()->emp_name }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold">WORK TYPE</label>
                        <select name="work_type" id="workTypeSelect" class="form-select" required>
                            <option value="Office">Office</option>
                            <option value="Work From Home">Work From Home</option>
                            <option value="Permission">Permission</option>
                        </select>
                    </div>
                </div>

                <div id="extraFields" class="row mb-4 d-none bg-light p-3 rounded border">
                    <div class="col-md-6 mb-3 d-none" id="permDurationDiv">
                        <label class="small fw-bold">Permission Duration</label>
                        <select name="permission_duration" class="form-select">
                            <option value="1 Hour">1 Hour</option><option value="2 Hours">2 Hours</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3 d-none" id="remWorkTypeDiv">
                        <label class="small fw-bold">Remaining Work Type</label>
                        <select name="remaining_work_type" class="form-select">
                            <option value="Office">Office</option><option value="WFH">WFH</option>
                        </select>
                    </div>
                    <div class="col-md-12 d-none" id="reasonDiv">
                        <label class="small fw-bold">Reason</label>
                        <textarea name="reason" class="form-control" rows="2" placeholder="Describe the reason..."></textarea>
                    </div>
                </div>

                <div id="task-wrapper">
                    <div class="task-row card mb-3 border-primary shadow-sm" style="border-left: 5px solid #0d6efd !important;">
                        <div class="card-body p-3 row align-items-center">
                            <div class="col-md-11">
                                <div class="row g-3 mb-2">
                                    <div class="col-md-3"><label class="small fw-bold text-muted">Project*</label>
                                        <select name="project_name[]" class="form-select form-select-sm" required>
                                            <option value="">Select Project</option>
                                            @foreach($projects as $p)<option value="{{ $p->projectname }}">{{ $p->projectname }}</option>@endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3"><label class="small fw-bold text-muted">Module</label><input type="text" name="module_name[]" class="form-control form-select-sm"></div>
                                    <div class="col-md-6"><label class="small fw-bold text-muted">Task Description</label><textarea name="task_description[]" class="form-control form-select-sm" rows="1" required></textarea></div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-2"><label class="small fw-bold text-muted">Status</label><select name="status[]" class="form-select form-select-sm"><option value="Pending">Pending</option><option value="In-progress">In-progress</option><option value="Completed">Completed</option></select></div>
                                    <div class="col-md-1"><label class="small fw-bold text-muted">Hrs</label><input type="number" step="0.1" name="target_hours[]" class="form-control form-select-sm"></div>
                                    <div class="col-md-3"><label class="small fw-bold text-muted">Remarks</label><input type="text" name="remarks[]" class="form-control form-select-sm"></div>
                                    <div class="col-md-3"><label class="small fw-bold text-muted">Start Time</label><input type="datetime-local" name="task_start[]" class="form-control form-select-sm"></div>
                                    <div class="col-md-3"><label class="small fw-bold text-muted">End Time</label><input type="datetime-local" name="task_end[]" class="form-control form-select-sm"></div>
                                </div>
                            </div>
                            <div class="col-md-1 text-center border-start">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-task-btn border-0"><i class="fas fa-trash-alt fa-lg"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary btn-sm px-4 rounded-pill mt-2 shadow-sm" id="add-task-btn">
                    <i class="fas fa-plus-circle me-1"></i> Add Task
                </button>

                <div class="text-center mt-5 border-top pt-4">
                    <button type="submit" class="btn btn-success px-5 py-2 fw-bold shadow-sm rounded-pill btn-lg">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#workTypeSelect').on('change', function() {
            let type = $(this).val();
            $('#extraFields, #reasonDiv, #permDurationDiv, #remWorkTypeDiv').addClass('d-none');
            if (type === 'Work From Home') $('#extraFields, #reasonDiv').removeClass('d-none');
            else if (type === 'Permission') $('#extraFields, #reasonDiv, #permDurationDiv, #remWorkTypeDiv').removeClass('d-none');
        });
        $("#add-task-btn").click(function() {
            let nr = $(".task-row:first").clone();
            nr.find("input, textarea").val(""); nr.find("select").val("");
            $("#task-wrapper").append(nr);
        });
        $(document).on("click", ".remove-task-btn", function() {
            if ($(".task-row").length > 1) $(this).closest(".task-row").remove();
            else alert("At least one task required.");
        });
    });
</script>
@endsection