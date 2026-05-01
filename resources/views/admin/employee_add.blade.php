@extends('layouts.app')

@section('content')
<h4>Employee Master</h4>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.employee.list') }}">Employee Master Report</a></li>
        <li class="breadcrumb-item active">Add Employee</li>
    </ol>
</nav>

<div class="card shadow-sm mt-3">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="fas fa-user-plus"></i> Add Employee</h5>
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
        <form action="{{ route('admin.employee.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <!-- Column 1 -->
                <div class="col-md-4">
                    <label>Employee Name</label>
                    <input type="text" name="emp_name" class="form-control" placeholder="Enter Employee Name" required>
                </div>
                <div class="col-md-4">
                    <label>Mobile</label>
                    <input type="text" name="mobile" class="form-control" placeholder="Enter Mobile" required>
                </div>
                <div class="col-md-4">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter Email">
                </div>

                <!-- Address spans 2 columns based on your image -->
                <div class="col-md-4">
                    <label>Address</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Address"></textarea>
                </div>
                <div class="col-md-4">
                    <label>City</label>
                    <input type="text" name="city" class="form-control" placeholder="Enter City">
                    <label class="mt-2">Pincode</label>
                    <input type="text" name="pincode" class="form-control" placeholder="Pincode">
                </div>
                <div class="col-md-4">
                    <label>State</label>
                    <select name="state" class="form-control">
                        <option>Select State Name</option>
                        <option value="Tamil Nadu">Tamil Nadu</option>
                        <option value="Kerala">Kerala</option>
                    </select>
                </div>

                <!-- Work Info -->
                <div class="col-md-4">
                    <label>Department</label>
                    <select name="department" class="form-control">
                        <option>Select Department</option>
                        <option value="Production">Production</option>
                        <option value="Sales">Sales</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Designation</label>
                    <select name="designation" class="form-control">
                        <option>Select Designation</option>
                        <option value="Junior Developer">Junior Developer</option>
                        <option value="Project Manager">Project Manager</option>
                    </select>
                </div>
                <div class="col-md-4"></div> <!-- Empty space -->

                <!-- Bank Info -->
                <div class="col-md-4">
                    <label>Account Number</label>
                    <input type="text" name="accno" class="form-control" placeholder="Enter Account Number">
                </div>
                <div class="col-md-4">
                    <label>Bank Name</label>
                    <input type="text" name="bankname" class="form-control" placeholder="Enter Bank Name">
                </div>
                <div class="col-md-4">
                    <label>IFSC Code</label>
                    <input type="text" name="ifsc" class="form-control" placeholder="Enter IFSC Code">
                </div>
                <div class="col-md-4">
                    <label>Branch</label>
                    <input type="text" name="branch" class="form-control" placeholder="Enter Branch Name">
                </div>
                
                <hr class="mt-4">

                <!-- Documents (Image 6 section) -->
                <h6 class="mt-3">Upload Documents</h6>
                <div class="col-md-4">
                    <label>Employee Photo</label>
                    <input type="file" name="photo" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Aadhar Card</label>
                    <input type="file" name="aadhar" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Passbook Front Page</label>
                    <input type="file" name="passbook" class="form-control">
                </div>

                <h6 class="mt-4">Login Credentials</h6>
                <div class="col-md-4">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
                </div>
                <div class="col-md-4">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
                </div>
                <div class="col-md-4">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                </div>

                <!-- Submit Button -->
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2" style="background-color: #3b5998; border: none;">Add Employee</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection