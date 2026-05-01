@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 text-dark">Product Master</h5>
        <small class="text-secondary"><a href="{{ route('admin.dashboard') }}">Home</a> / Product Master</small>
    </div>
    <a href="{{ route('admin.product.list') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-list"></i> View Product Details
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0"><i class="fas fa-box"></i> Product Master</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.product.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="productname" class="form-control" value="{{ $product->productname }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Select Category</label>
                    <select name="category" class="form-select">
                        <option value="Software" {{ $product->category == 'Software' ? 'selected' : '' }}>Software</option>
                        <option value="Hardware" {{ $product->category == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                   <div class="col-md-6">
                 <label class="form-label">Hardware Details</label>
                  <!-- Ensure name="hardware_details" matches your controller -->
                  <textarea name="hardware_details" class="form-control" rows="3">{{ $product->hardware_details }}</textarea>
             </div>
                <div class="col-md-6">
                    <label class="form-label">Software Details</label>
                    <textarea name="software_details" class="form-control" rows="3">{{ $product->software_details }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Amount</label>
                    <input type="number" step="0.01" name="amt" class="form-control" value="{{ $product->amt }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">GST Value</label>
                    <div class="input-group">
                        <input type="number" name="gst" class="form-control" value="{{ $product->gst }}">
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-dark px-5 py-2 rounded-pill">Update Product</button>
            </div>
        </form>
    </div>
</div>
@endsection