<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\ForgotPasswordController;



// --- 1. Public Routes ---
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']); 
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.otp');

// --- 2. Shared Protected Routes (Change Password) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

// --- 3. Admin Routes (role:admin) ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Employee Master
    Route::get('/employee-master', [AdminController::class, 'employeeList'])->name('employee.list');
    Route::get('/employee-master/add', [AdminController::class, 'addEmployee'])->name('employee.add');
    Route::post('/employee-master/store', [AdminController::class, 'storeEmployee'])->name('employee.store');
    Route::get('/employee-master/edit/{id}', [AdminController::class, 'editEmployee'])->name('employee.edit');
    Route::put('/employee-master/update/{id}', [AdminController::class, 'updateEmployee'])->name('employee.update');
    Route::delete('/employee-master/delete/{id}', [AdminController::class, 'deleteEmployee'])->name('employee.delete');
    Route::post('/employee-master/update-status', [AdminController::class, 'updateStatus'])->name('employee.updateStatus');

    // Project Master
    Route::get('/project-master', [AdminController::class, 'projectList'])->name('project.list');
    Route::get('/project-master/add', [AdminController::class, 'addProject'])->name('project.add');
    Route::post('/project-master/store', [AdminController::class, 'storeProject'])->name('project.store');
    Route::get('/project-master/edit/{id}', [AdminController::class, 'editProject'])->name('project.edit');
    Route::put('/project-master/update/{id}', [AdminController::class, 'updateProject'])->name('project.update');
    Route::delete('/project-master/delete/{id}', [AdminController::class, 'deleteProject'])->name('project.delete');
    Route::post('/project-master/delete-selected', [AdminController::class, 'deleteSelectedProjects'])->name('project.deleteSelected');

    // Product Master
    Route::get('/product-master', [AdminController::class, 'productList'])->name('product.list');
    Route::get('/product-master/add', [AdminController::class, 'addProduct'])->name('product.add');
    Route::post('/product-master/store', [AdminController::class, 'storeProduct'])->name('product.store');
    Route::get('/product-master/edit/{id}', [AdminController::class, 'editProduct'])->name('product.edit');
    Route::put('/product-master/update/{id}', [AdminController::class, 'updateProduct'])->name('product.update');
    Route::delete('/product-master/delete/{id}', [AdminController::class, 'deleteProduct'])->name('product.delete');
    Route::post('/product-master/delete-selected', [AdminController::class, 'deleteSelectedProducts'])->name('product.deleteSelected');

    // Query Management
    Route::get('/queries', [QueryController::class, 'adminList'])->name('queries.list');
    Route::get('/queries/add', [QueryController::class, 'create'])->name('queries.add');
    Route::post('/queries', [QueryController::class, 'store'])->name('queries.store');
    Route::get('/queries/{query}/edit', [QueryController::class, 'edit'])->name('queries.edit');
    Route::put('/queries/{query}', [QueryController::class, 'update'])->name('queries.update');
    Route::delete('/queries/{query}', [QueryController::class, 'destroy'])->name('queries.destroy');

    // Team Leave Routes
    Route::get('/team-leave', [AdminController::class, 'leaveIndex'])->name('leave.index');
    Route::post('/team-leave/store', [AdminController::class, 'leaveStore'])->name('leave.store');
    Route::delete('/team-leave/{id}', [AdminController::class, 'leaveDelete'])->name('leave.delete');
    Route::post('/holiday/store', [AdminController::class, 'holidayStore'])->name('holiday.store');

    Route::get('/attendance-report/export', [AdminController::class, 'attendanceExport'])->name('attendance.export');
    
    // Attendance Report
    Route::get('/attendance-report', [AdminController::class, 'attendanceReport'])->name('attendance.report');
    Route::get('/attendance-report/pdf', [AdminController::class, 'attendancePdf'])->name('attendance.pdf');

    // Daily Report Special Routes
    Route::put('/daily-reports/{id}', [AdminController::class, 'dailyReportUpdate'])->name('daily_report.update');
    Route::delete('/daily-reports/{id}', [AdminController::class, 'dailyReportDelete'])->name('daily_report.delete');
    Route::get('/daily-work-report', [AdminController::class, 'dailyReportIndex'])->name('daily_report.index');
    Route::post('/daily-work-report/store', [AdminController::class, 'dailyReportStore'])->name('daily_report.store');

    // Team Report View & Import (FIXED)
    // Loads the page
    Route::get('/team-report-view', [AdminController::class, 'teamReportView'])->name('team_report.index');
    // Handles the Excel Import button
    Route::post('/team-report-import', [AdminController::class, 'importExcel'])->name('team_report.import');


    Route::get('/admin/queries/project-add', [QueryController::class, 'addProjectQuery'])->name('queries.addProject');
Route::get('/admin/queries/product-add', [QueryController::class, 'addProductQuery'])->name('queries.addProduct');
       

 });


// --- 4. Employee Routes (role:employee) ---
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');
    Route::get('/queries', [QueryController::class, 'employeeList'])->name('queries');
    Route::put('/queries/{id}/update', [QueryController::class, 'employeeUpdate'])->name('queries.update');

    Route::get('attendance-report', [EmployeeController::class, 'attendanceReport'])->name('attendance_report');

    Route::get('/daily-reports', [EmployeeController::class, 'dailyReportIndex'])->name('daily_report.index');
    Route::get('/daily-reports/add', [EmployeeController::class, 'dailyReportCreate'])->name('daily_report.create');
    Route::post('/daily-reports/store', [EmployeeController::class, 'dailyReportStore'])->name('daily_report.store');

    // FIXED: Removed "/employee" from URL and "employee." from Name because Group handles it
    Route::get('/queries/add', [QueryController::class, 'employeeAdd'])->name('queries.add');
    Route::post('/queries/store', [QueryController::class, 'employeeStore'])->name('queries.store');
});