<style>
    /* Styling to match the Layout and Size of Main Sidebar Items */
    .nav-sub-item {
        display: flex;
        align-items: center;
        /* Vertical padding matches main links (approx 10px-12px) */
        padding: 10px 0 10px 45px; 
        color: #adb5bd !important;
        text-decoration: none;
        /* Font size matches main sidebar text */
        font-size: 14px; 
        transition: all 0.2s ease-in-out;
    }

    /* Brighter White on Hover - Matches main sidebar hover glow */
    .nav-sub-item:hover {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.1); /* Subtle highlight like main items */
    }

    /* Keep the icon size consistent with main items */
    .nav-sub-item i {
        font-size: 12px;
        width: 20px;
    }

    /* Active state brightness */
    .nav-sub-item.active-sub {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.1);
    }
</style>
    
    
    <nav class="sidebar" style="width: 250px; background-color: #0f172a; color: #cbd5e1; display: flex; flex-direction: column; overflow-y: auto; min-height: 100vh;">
        <div class="sidebar-header text-center py-3 border-bottom border-secondary bg-white text-dark">
            <h4 class="mb-0 fw-bold" style="color: #004d99;">
                <i class="fas fa-handshake"></i> RTS CRM
            </h4>
        </div>
        
        <ul class="list-unstyled mt-3" style="padding: 0; margin: 0;">
            <!-- Dashboard Link -->
            <li>
                <a href="{{ route(Auth::user()->designation == 'Project Manager' ? 'admin.dashboard' : 'employee.dashboard') }}" 
                style="display: block; padding: 14px 20px; color: #cbd5e1; text-decoration: none; {{ request()->routeIs('*.dashboard') ? 'border-left: 4px solid #0ea5e9; background-color: #1e293b; color: #ffffff;' : '' }}">
                    <i class="fas fa-tachometer-alt" style="margin-right: 10px; width: 20px; text-align: center;"></i> Dashboard
                </a>
            </li>
            
            @if(Auth::user()->designation == 'Project Manager')
                <!-- ADMIN MENU -->
                <li>
                    <a data-bs-toggle="collapse" href="#masterMenu" role="button" style="display: block; padding: 14px 20px; color: #cbd5e1; text-decoration: none;">
                        <i class="fas fa-cogs" style="margin-right: 10px; width: 20px; text-align: center;"></i> Master <i class="fas fa-caret-down float-end mt-1"></i>
                    </a>
                    <div class="collapse show" id="masterMenu">
                        <ul class="list-unstyled">
                            <!-- Product Master (FIXED LINK) -->
                            <li>
                                <a href="{{ route('admin.product.list') }}" 
                                style="display: block; padding: 10px 20px 10px 55px; text-decoration: none; font-size: 13.5px; 
                                {{ request()->routeIs('admin.product.list') ? 'color: #ffffff; font-weight: bold; background-color: #1e293b; border-left: 4px solid #0ea5e9;' : 'color: #cbd5e1;' }}">
                                    <i class="fas fa-box" style="margin-right: 10px;"></i> Product Master
                                </a>
                            </li>
                            <!-- Project Master -->
                        
            <li>
        <a href="{{ route('admin.project.list') }}" 
        style="display: block; padding: 10px 20px 10px 55px; text-decoration: none; font-size: 13.5px; 
        {{ request()->routeIs('admin.project.*') ? 'color: #ffffff; font-weight: bold; background-color: #1e293b; border-left: 4px solid #0ea5e9;' : 'color: #cbd5e1;' }}">
            <i class="fas fa-project-diagram" style="margin-right: 10px;"></i> Project Master
        </a>
            </li>
                            <!-- Employee Master -->
                            <li>
                                <a href="{{ route('admin.employee.list') }}" 
                                style="display: block; padding: 10px 20px 10px 55px; text-decoration: none; font-size: 13.5px; 
                                {{ request()->routeIs('admin.employee.*') ? 'color: #ffffff; font-weight: bold; background-color: #1e293b; border-left: 4px solid #0ea5e9;' : 'color: #cbd5e1;' }}">
                                    <i class="fas fa-users" style="margin-right: 10px;"></i> Employee Master
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li><a href="{{ route('admin.attendance.report') }}" style="display: block; padding: 14px 20px; color: #cbd5e1; text-decoration: none;"><i class="fas fa-calendar-alt" style="margin-right: 10px; width: 20px; text-align: center;"></i> Attendance Report</a></li>
                <li><a href="#" style="display: block; padding: 14px 20px; color: #cbd5e1; text-decoration: none;"><i class="fas fa-list-alt" style="margin-right: 10px; width: 20px; text-align: center;"></i> Project Production List</a></li>
                <li><a href="{{ route('admin.leave.index') }}" style="display: block; padding: 14px 20px; color: #cbd5e1; text-decoration: none;"><i class="fas fa-user-clock" style="margin-right: 10px; width: 20px; text-align: center;"></i> Team Leave / Half Day</a></li>
                                <!-- Sidebar View Query Section -->
                        <!-- View Query Menu Group -->
<li class="nav-item">
    <!-- Parent Link -->
    <a href="{{ route('admin.queries.list') }}" class="nav-link {{ request()->is('admin/queries*') ? 'active' : '' }}">
        <i class="fas fa-eye me-2"></i>
        <span>View Query</span>
    </a>

    <!-- Sub Items - Layout and Size matched to main buttons -->
    <div class="sub-menu-box">
        <a href="{{ route('admin.queries.addProject') }}" 
           class="nav-sub-item {{ request()->is('admin/queries/project-add*') ? 'active-sub' : '' }}">
            <i class="fas fa-caret-right me-2"></i>
            <span>Select Project</span>
        </a>
        
        <a href="{{ route('admin.queries.addProduct') }}" 
           class="nav-sub-item {{ request()->is('admin/queries/product-add*') ? 'active-sub' : '' }}">
            <i class="fas fa-caret-right me-2"></i>
            <span>Select Product</span>
        </a>
    </div>
</li>




                <li><a href="{{ route('admin.daily_report.index') }}" style="display: block; padding: 14px 20px; color: #cbd5e1; text-decoration: none;"><i class="fas fa-file-alt" style="margin-right: 10px; width: 20px; text-align: center;"></i> Daily Reports</a></li>

            @else
                <!-- EMPLOYEE MENU -->
                <li><a href="{{ route('employee.attendance_report') }}" style="display: block; padding: 14px 20px; color: #cbd5e1; text-decoration: none;"><i class="fas fa-calendar-alt" style="margin-right: 10px; width: 20px; text-align: center;"></i> Attendance Report</a></li>
                <li><a href="#" style="display: block; padding: 14px 20px; color: #cbd5e1; text-decoration: none;"><i class="fas fa-list-alt" style="margin-right: 10px; width: 20px; text-align: center;"></i> Project Production List</a></li>
                <li><a href="{{ route('employee.daily_report.index') }}" style="display: block; padding: 14px 20px; color: #cbd5e1; text-decoration: none;"><i class="fas fa-file-alt" style="margin-right: 10px; width: 20px; text-align: center;"></i> Daily Reports</a></li>
                <li><a href="{{ route('employee.queries') }}" style="display: block; padding: 14px 20px; color: #cbd5e1; text-decoration: none;"><i class="fas fa-question-circle" style="margin-right: 10px; width: 20px; text-align: center;"></i> Query Details</a></li>
            @endif
        </ul>
    </nav>