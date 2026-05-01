<header class="topbar" style="background-color: #ffffff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0;">
    <div>
        <!-- Left side space -->
    </div> 
    
    <div class="dropdown">
        <a href="#" class="text-dark text-decoration-none dropdown-toggle fw-bold" data-bs-toggle="dropdown" style="font-size: 15px;">
            <i class="fas fa-bell me-3 fs-5 text-dark"></i> 
            Welcome, {{ Auth::user()->emp_name }} 
            <i class="fas fa-user-circle ms-1 fs-5"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
            <li><a class="dropdown-item py-2" href="#"><i class="fas fa-key text-secondary me-2"></i> Change Password</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-danger py-2" href="{{ route('logout') }}">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</header>