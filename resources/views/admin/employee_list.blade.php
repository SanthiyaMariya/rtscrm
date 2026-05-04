@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 text-dark">Employee Master Report</h5>
        <small class="text-primary"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a> / Employee Master Report</small>
    </div>
    <a href="{{ route('admin.employee.add') }}" class="btn btn-secondary rounded-pill px-4" style="background-color: #5c6bc0; border: none;">
        <i class="fas fa-plus-circle"></i> Add Employee
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 py-3">
        <h6><i class="fas fa-user-friends"></i> Employee Report</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center mb-0" style="font-size: 13px;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 30px;"><input type="checkbox" class="form-check-input"></th>
                        <th style="width: 40px;">#</th>
                        <th>NAME</th>
                        <th>DEPARTMENT</th>
                        <th>DESIGNATION</th>
                        <th>MOBILE</th>
                        <th>EMAIL</th>
                        <th>CITY</th>
                        <th>STATE</th>
                        <th>STATUS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $index => $employee)
                    <tr>
                        <td><input type="checkbox" class="form-check-input"></td>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-start">{{ $employee->emp_name }}</td>
                        <td>{{ $employee->department }}</td>
                        <td>{{ $employee->designation }}</td>
                        <td>{{ $employee->mobile }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->city }}</td>
                        <td>{{ $employee->state }}</td>
                        <td>
                            <!-- Status Toggle Switch -->
                            

                           <div class="form-check form-switch d-flex justify-content-center">
    <input class="form-check-input" type="checkbox" 
           onchange="updateStatus({{ $employee->id }}, this.checked)" 
           {{ $employee->status == 1 ? 'checked' : '' }}>
</div>
                        <td>
                            <a href="{{ route('admin.employee.edit', $employee->id) }}" class="btn btn-sm btn-outline-primary py-0 px-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.employee.delete', $employee->id) }}" method="POST" style="display:inline;">
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
    </div>
</div>
@endsection


<script>
function updateStatus(id, isChecked) {
    fetch("{{ route('admin.employee.updateStatus') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ id: id, status: isChecked ? 1 : 0 })
    });
}
</script>