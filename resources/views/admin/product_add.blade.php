@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 text-dark">Product Master</h5>
        <small class="text-primary"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a> / Product Master</small>
    </div>
    <a href="{{ route('admin.product.list') }}" class="btn btn-secondary rounded-pill px-4" style="background-color: #5c6bc0; border: none;">
        <i class="fas fa-eye"></i> View Product Details
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom">
        <h6 class="mb-0 fw-bold"><i class="fas fa-box"></i> Product Master</h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.product.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label" style="font-size: 13px;">Product Name</label>
                    <input type="text" name="productname" class="form-control form-control-sm" placeholder="Enter Product Name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" style="font-size: 13px;">Select Category</label>
                    <select name="category" class="form-select form-select-sm" required>
                        <option value="" disabled selected>Select Option</option>
                        <option value="Software">Software</option>
                        <option value="Hardware">Hardware</option>
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label" style="font-size: 13px;">Description</label>
                    <textarea name="description" class="form-control form-control-sm" rows="4" placeholder="Description"></textarea>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 13px;">Amount</label>
                        <input type="number" step="0.01" name="amt" class="form-control form-control-sm" placeholder="Enter Amount" required>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 13px;">GST Value</label>
                        <div class="input-group input-group-sm">
                            <input type="number" name="gst" class="form-control" placeholder="Enter GST Value" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn text-white rounded-pill px-5" style="background-color: #2b3a67;">Add Product</button>
            </div>
        </form>
    </div>
</div>
@endsection