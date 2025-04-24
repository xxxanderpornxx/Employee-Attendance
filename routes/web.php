<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;
// use App\Models\empuser;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeshiftController;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\AttendanceController;


// Route to show the login form

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Route to handle the login submission
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Route to handle logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard route
Route::get('/dashboard', function () {
    if (!session('user')) {
        return redirect('/login')->withErrors(['message' => 'Please log in to access the dashboard.']);
    }

    return view('main.dashboard');
})->name('dashboard');

Route::get('/components/layout', function () {
    return view('components.layout');
});

Route::get('/components/loginlayout', function () {
    return view('components.loginlayout');
});

// Route to show the registration form
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Route to handle the registration submission
Route::post('/register', [RegisterController::class, 'register'])->name('register');



// Dashboard route
Route::get('/dashboard', function () {
    return view('main.dashboard');
})->name('dashboard');

// Attendance route
Route::get('/attendance', function () {
    return view('main.attendance');
})->name('attendance');

// Employee route
Route::get('/employee', [EmployeeController::class, 'index'])->name('Employees.index');
Route::post('/employee', [EmployeeController::class, 'store'])->name('Employees.store');
Route::delete('/employee/{id}', [EmployeeController::class, 'destroy'])->name('Employees.destroy');
Route::put('/employee/{id}', [EmployeeController::class, 'update'])->name('Employees.update');
Route::get('/employee/{id}/edit', [EmployeeController::class, 'edit'])->name('Employees.edit');
Route::get('/employee/{id}', [EmployeeController::class, 'show'])->name('Employees.show');
Route::get('/employee/create', [EmployeeController::class, 'create'])->name('Employees.create');
Route::post('/employee/store', [EmployeeController::class, 'store'])->name('Employees.store');
Route::delete('/employee/{id}', [EmployeeController::class, 'destroy'])->name('Employees.destroy');
Route::get('/employee/{id}', [EmployeeController::class, 'show'])->name('Employees.show');
Route::post('/employees', [EmployeeController::class, 'store'])->name('Employees.store');
Route::post('/employees/generate-qr', [EmployeeController::class, 'generateQrCode'])->name('employees.generateQrCode');

// Shifts route
Route::get('/shift', function () {
    return view('main.shift');
})->name('shift');
Route::get('/shift', [ShiftController::class, 'index'])->name('shifts.index');
Route::post('/shift', [ShiftController::class, 'store'])->name('shifts.store');
Route::delete('/shift/{id}', [ShiftController::class, 'destroy'])->name('shifts.destroy');
Route::put('/shift/{id}', [ShiftController::class, 'update'])->name('shifts.update');
Route::get('/shift/{id}/edit', [ShiftController::class, 'edit'])->name('shifts.edit');

// Payroll route
Route::get('/payroll', function () {
    return view('main.payroll');
})->name('payroll');

// Department Routes
Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/positions', [DepartmentController::class, 'index'])->name('positions.index'); // Add this line
Route::post('/departments', [DepartmentController::class, 'storeDepartment'])->name('departments.store');
Route::put('/departments/{id}', [DepartmentController::class, 'updateDepartment'])->name('departments.update');
Route::delete('/departments/{id}', [DepartmentController::class, 'destroyDepartment'])->name('departments.destroy');

// Position Routes
Route::post('/positions', [DepartmentController::class, 'storePosition'])->name('positions.store');
Route::put('/positions/{id}', [DepartmentController::class, 'updatePosition'])->name('positions.update');
Route::delete('/positions/{id}', [DepartmentController::class, 'destroyPosition'])->name('positions.destroy');

// assign shift route
Route::post('/assign-shift', [EmployeeshiftController::class, 'assignShiftToEmployee'])->name('assignShift');

// Route to handle the QR code scanning
Route::post('/attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
Route::post('/attendance/process-qr-code', [AttendanceController::class, 'processQRCode'])->name('attendance.processQRCode');
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');