@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Add New Project</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.project.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Project Name</label>
                    <input type="text" name="projectname" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Client Name</label>
                    <input type="text" name="clientname" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Platform</label>
                    <input type="text" name="platform" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Language</label>
                    <input type="text" name="language" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Project</button>
            <a href="{{ route('admin.project.list') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection