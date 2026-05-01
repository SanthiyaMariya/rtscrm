@extends('layouts.app') {{-- Or whatever your main layout is --}}

@section('content')
<div class="container d-flex justify-content-center align-items-center py-5">
    <div class="card border-0 shadow-lg" style="background-color: #213555; border-radius: 15px; width: 100%; max-width: 450px;">
        <div class="card-body p-5">
            <h3 class="text-white text-center mb-4 fw-bold">Change Password</h3>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success py-2 text-center" style="font-size: 14px;">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2" style="font-size: 13px;">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                
                <!-- Current Password -->
                <div class="mb-3">
                    <label class="form-label text-white small">Current Password</label>
                    <input type="password" name="current_password" class="form-control border-0" style="background-color: #f0f4f8;" required>
                </div>

                <!-- New Password -->
                <div class="mb-3">
                    <label class="form-label text-white small">New Password</label>
                    <input type="password" name="new_password" class="form-control border-0" style="background-color: #f0f4f8;" required>
                </div>

                <!-- Confirm New Password -->
                <div class="mb-4">
                    <label class="form-label text-white small">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control border-0" style="background-color: #f0f4f8;" required>
                </div>

                <button type="submit" class="btn btn-info w-100 fw-bold text-dark rounded-pill py-2" style="background-color: #00e5ff; border: none;">
                    Update Password
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('employee.dashboard') }}" class="text-decoration-none small" style="color: #00e5ff;">Back to Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection