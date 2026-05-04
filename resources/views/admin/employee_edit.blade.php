@extends('layouts.app')

@section('content')
<h4>Employee Master</h4>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.employee.list') }}">Employee Master Report</a></li>
        <li class="breadcrumb-item active">Edit Employee</li>
    </ol>
</nav>

<div class="card shadow-sm mt-3">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-user-edit"></i> Edit Employee: {{ $employee->emp_name }}</h5>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card-body">
        <form action="{{ route('admin.employee.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                <!-- Column 1: Basic Info -->
                <div class="col-md-4">
                    <label class="fw-bold">Employee Name</label>
                    <input type="text" name="emp_name" class="form-control" value="{{ $employee->emp_name }}" required>
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">Mobile</label>
                    <input type="text" name="mobile" class="form-control" value="{{ $employee->mobile }}" required>
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $employee->email }}">
                </div>

                <!-- Address Section -->
                <div class="col-md-4">
                    <label class="fw-bold">Address</label>
                    <textarea name="address" class="form-control" rows="4">{{ $employee->address }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">City</label>
                    <input type="text" name="city" class="form-control" value="{{ $employee->city }}">
                    
                    <label class="mt-2 fw-bold">Pincode</label>
                    <input type="text" name="pincode" class="form-control" value="{{ $employee->pincode }}">
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">State</label>
                    <select name="state" class="form-control">
                        <option value="">Select State</option>
                        <option value="Tamil Nadu" {{ $employee->state == 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                        <option value="Kerala" {{ $employee->state == 'Kerala' ? 'selected' : '' }}>Kerala</option>
                    </select>
                </div>

                <!-- Work Info -->
                <div class="col-md-4">
                    <label class="fw-bold">Department</label>
                    <select name="department" class="form-control">
                        <option value="Production" {{ $employee->department == 'Production' ? 'selected' : '' }}>Production</option>
                        <option value="Sales" {{ $employee->department == 'Sales' ? 'selected' : '' }}>Sales</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">Designation</label>
                    <select name="designation" class="form-control">
                        <option value="Junior Developer" {{ $employee->designation == 'Junior Developer' ? 'selected' : '' }}>Junior Developer</option>
                        <option value="Project Manager" {{ $employee->designation == 'Project Manager' ? 'selected' : '' }}>Project Manager</option>
                    </select>
                </div>
                <div class="col-md-4"></div>

                <!-- Bank Info -->
                <div class="col-md-4">
                    <label class="fw-bold">Account Number</label>
                    <input type="text" name="accno" class="form-control" value="{{ $employee->accno }}">
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">Bank Name</label>
                    <input type="text" name="bankname" class="form-control" value="{{ $employee->bankname }}">
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">IFSC Code</label>
                    <input type="text" name="ifsc" class="form-control" value="{{ $employee->ifsc }}">
                </div>
                <div class="col-md-4">
                    <label class="fw-bold">Branch</label>
                    <input type="text" name="branch" class="form-control" value="{{ $employee->branch }}">
                </div>
                
                <hr class="mt-4">

                <!-- Documents Section -->
                <h6 class="mt-2 fw-bold text-primary">Upload Documents (Leave blank to keep existing)</h6>
                <div class="col-md-4">
                    <label>Employee Photo</label>
                    <input type="file" name="photo" class="form-control">
                    @if($employee->photo) <small class="text-success">Current: {{ $employee->photo }}</small> @endif
                </div>
                <div class="col-md-4">
                    <label>Aadhar Card</label>
                    <input type="file" name="aadhar" class="form-control">
                    @if($employee->aadhar) <small class="text-success">Current: {{ $employee->aadhar }}</small> @endif
                </div>
                <div class="col-md-4">
                    <label>Passbook Front Page</label>
                    <input type="file" name="passbook" class="form-control">
                    @if($employee->passbook) <small class="text-success">Current: {{ $employee->passbook }}</small> @endif
                </div>

                <h6 class="mt-4 fw-bold text-primary">Login Credentials</h6>
                <div class="col-md-4">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="{{ $employee->username }}" required>
                </div>
                <div class="col-md-4">
                    <label>New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                </div>
                <div class="col-md-4">
                    <label>Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Leave blank to keep current">
                </div>

                <!-- Submit Button -->
                <div class="col-12 text-center mt-5">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2" style="background-color: #3b5998; border: none;">
                        Update Employee Details
                    </button>
                    <a href="{{ route('admin.employee.list') }}" class="btn btn-light rounded-pill px-5 py-2 border">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection