@extends('layouts.app')
@section('content')

@if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 text-dark">Project Master Report</h5>
        <small class="text-primary"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a> / Project Master Report</small>
    </div>
    <a href="{{ route('admin.project.add') }}" class="btn btn-secondary rounded-pill px-4" style="background-color: #5c6bc0; border: none;">
        <i class="fas fa-plus-circle"></i> Add Projects
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row mb-3">
            <!-- Filter Section (Left as is) -->
            <div class="col-md-4">
                <form action="{{ route('admin.project.list') }}" method="GET" id="filterForm">
                    <label>Filter by Status:</label>
                    <select name="status" id="statusFilter" class="form-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                    </select>
                </form>
            </div>

            <!-- Search Section -->
            <div class="col-md-4 ms-auto text-end">
                <label class="form-label fw-bold">Search:</label>
                <div class="input-group">
                    <input type="text" id="manualSearch" class="form-control" placeholder="Type to search...">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
            </div>
        </div>

        <!-- Table - ADDED id="projectTable" -->
        <div class="table-responsive">
            <table id="projectTable" class="table table-bordered table-hover align-middle text-center" style="font-size: 12px;">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <!-- ADDED id="selectAll" -->
                        <th><input type="checkbox" id="selectAll" class="form-check-input"></th>
                        <th># <i class="fas fa-sort"></i></th>
                        <th>PROJECT NAME <i class="fas fa-sort"></i></th>
                        <th>PLATFORM <i class="fas fa-sort"></i></th>
                        <th>LANGUAGE <i class="fas fa-sort"></i></th>
                        <th>CONFIRMATION <i class="fas fa-sort"></i></th>
                        <th>DESCRIPTION <i class="fas fa-sort"></i></th>
                        <th>CREATED DATE <i class="fas fa-sort"></i></th>
                        <th>ACTIONS <i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $index => $project)
                    <tr>
                        <td><input type="checkbox" class="form-check-input select-item" value="{{ $project->id }}"></td>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $project->projectname }}</td>
                        <td>{{ $project->platform }}</td>
                        <td>{{ $project->language }}</td>
                        <td>{{ $project->projectconfirmation }}</td>
                        <td>{{ $project->description }}</td>
                        <td>{{ $project->created_at }}</td>
                        <td>
                            <a href="{{ route('admin.project.edit', $project->id) }}" class="btn btn-sm btn-outline-primary py-0 px-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.project.delete', $project->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="text-end mt-3">
            <button type="button" id="delete_selected_project_btn" class="btn btn-danger btn-sm rounded">Delete Selected</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable on the ID we just added
    var table = $('#projectTable').DataTable({
        "dom": 'lrtip', 
        "pageLength": 10,
        "ordering": true
    });

    // Custom Search Functionality
    $('#manualSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    // "Select All" Checkbox
    $('#selectAll').on('click', function() {
        $('.select-item').prop('checked', this.checked);
    });

    // Bulk Delete
    $('#delete_selected_project_btn').on('click', function() {
        let ids = [];
        $('.select-item:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length <= 0) {
            alert("Please select at least one row.");
        } else {
            if (confirm("Are you sure you want to delete selected projects?")) {
                $.ajax({
                    url: "{{ route('admin.project.deleteSelected') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: ids
                    },
                    success: function (response) {
                        alert("Deleted Successfully");
                        location.reload(); 
                    },
                    error: function (err) {
                        alert("An error occurred. Please try again.");
                    }
                });
            }
        }
    });
});
</script>
@endsection