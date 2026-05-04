@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header with Breadcrumbs and View Button -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0 text-dark">Project Master</h5>
            <small class="text-primary">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a> / Project Master
            </small>
        </div>
        <a href="{{ route('admin.project.list') }}" class="btn btn-primary rounded-pill px-4" style="background-color: #5c6bc0; border: none;">
            <i class="fas fa-eye me-1"></i> View Project Details
        </a>
    </div>

    <!-- Main Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold"><i class="fas fa-book me-2"></i> Project Master</h6>
        </div>
        <div class="card-body p-4">
            {{-- Added enctype for file uploads --}}
            <form action="{{ route('admin.project.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Project Name</label>
                            <input type="text" name="projectname" class="form-control bg-light" value="{{ $project->projectname }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Description</label>
                            <textarea name="description" class="form-control bg-light" rows="5">{{ $project->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Language</label>
                            <input type="text" name="language" class="form-control bg-light" value="{{ $project->language }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Acceptance Upload (PDF / Image)</label>
                            <input type="file" name="acceptance_file" class="form-control bg-light">
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Platform</label>
                            <input type="text" name="platform" class="form-control bg-light" value="{{ $project->platform }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Project Confirmation</label>
                            <select name="status" class="form-select bg-light">
                                <option value="Pending" {{ $project->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Confirmed" {{ $project->status == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                            </select>
                        </div>
                        
                        {{-- Kept your Client Name field from your old code but styled it --}}
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Client Name</label>
                            <input type="text" name="clientname" class="form-control bg-light" value="{{ $project->clientname }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Quotation Upload (PDF / Image)</label>
                            <input type="file" name="quotation_file" class="form-control bg-light">
                        </div>
                    </div>
                </div>

                <!-- Centered Action Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5 rounded-pill py-2 fw-bold" style="background-color: #405189; border: none;">
                        Update Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection