@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 text-dark">Add Multiple Queries</h5>
        <small class="text-secondary"><a href="{{ route('admin.dashboard') }}">Home</a> / Query Report</small>
    </div>
    <a href="{{ route('admin.queries.list') }}" class="btn text-white rounded-pill px-4" style="background-color: #5c6bc0;">
        <i class="fas fa-eye"></i> View Query
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom">
        <h6 class="mb-0 fw-bold">Add / Assign New Query</h6>
    </div>
    <div class="card-body p-4">
        
        <form action="{{ route('admin.queries.store') }}" method="POST" id="multiQueryForm">
            @csrf
            
            <!-- This container will hold all the query rows -->
            <div id="queries-wrapper">
                <div class="query-item border rounded p-3 mb-4 bg-light shadow-sm">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-primary">Query #1</span>
                        <button type="button" class="btn btn-sm btn-danger remove-row-btn d-none"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Select Project</label>
                            <select name="projectname[]" class="form-select project-select">
                                <option value="">-- Select Project --</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->projectname }}">{{ $project->projectname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Select Product</label>
                            <select name="productname[]" class="form-select product-select">
                                <option value="">-- Select Product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->productname }}">{{ $product->productname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Assign To Employee</label>
                            <select name="assigned_to[]" class="form-select">
                                <option value="">-- Select Employee --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->emp_name }}">{{ $employee->emp_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Priority</label>
                            <select name="priority[]" class="form-select">
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Query Title</label>
                            <input type="text" name="query_title[]" class="form-control" placeholder="Enter title" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Assigned Date</label>
                            <input type="date" name="assigned_date[]" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Target Date (Deadline)</label>
                            <input type="date" name="target_date[]" class="form-control" required>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label small fw-bold">Query Details</label>
                            <textarea name="query_details[]" class="form-control" rows="2" placeholder="Describe the task..."></textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Status</label>
                            <select name="status[]" class="form-select">
                                <option value="0" selected>New</option>
                                <option value="1">In Progress</option>
                                <option value="2">Closed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- THE (+) BUTTON YOU ASKED FOR -->
            <div class="text-start mb-4">
                <button type="button" id="add-new-row" class="btn btn-success rounded-pill px-4">
                    <i class="fas fa-plus-circle"></i> Add Query
                </button>
            </div>

            <!-- THE FINAL SUBMIT BUTTON -->
            <div class="text-center mt-5 border-top pt-4">
                <button type="submit" class="btn btn-primary px-5 rounded-pill btn-lg" style="background-color: #5c6bc0; border: none;">
                    Save All Queries to Grid
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let rowCount = 1;

    document.getElementById('add-new-row').addEventListener('click', function() {
        rowCount++;
        const wrapper = document.getElementById('queries-wrapper');
        const firstRow = document.querySelector('.query-item');
        const newRow = firstRow.cloneNode(true);

        // Reset values in the new row
        newRow.querySelector('.badge').innerText = "Query #" + rowCount;
        newRow.querySelectorAll('input, textarea').forEach(input => {
            if(input.type !== 'date') input.value = '';
        });
        
        // Show the remove button for new rows
        const removeBtn = newRow.querySelector('.remove-row-btn');
        removeBtn.classList.remove('d-none');
        removeBtn.onclick = function() { newRow.remove(); };

        wrapper.appendChild(newRow);
    });

    // Validates that either Project or Product is picked for every row
    document.getElementById('multiQueryForm').onsubmit = function(e) {
        let rows = document.querySelectorAll('.query-item');
        let isValid = true;

        rows.forEach((row, index) => {
            let project = row.querySelector('.project-select').value;
            let product = row.querySelector('.product-select').value;
            if (project === "" && product === "") {
                alert("Error in Query #" + (index + 1) + ": Please select either a Project or a Product.");
                isValid = false;
            }
        });

        if (!isValid) e.preventDefault();
    };
</script>
@endsection