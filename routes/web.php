<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeschedulesController;
use App\Http\Controllers\EmployeeshiftController;

Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.create');

// Dashboard Route
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('main.dashboard');
    })->name('dashboard');

    // Employee routes
    Route::get('/employee', [EmployeeController::class, 'index'])->name('Employees.index');
    Route::post('/employee', [EmployeeController::class, 'store'])->name('Employees.store');
    Route::delete('/employee/{id}', [EmployeeController::class, 'destroy'])->name('Employees.destroy');
    Route::put('/employee/{id}', [EmployeeController::class, 'update'])->name('Employees.update');
    Route::get('/employee/{id}/edit', [EmployeeController::class, 'edit'])->name('Employees.edit');
    Route::get('/employee/{id}', [EmployeeController::class, 'show'])->name('Employees.show');
    Route::get('/employee/create', [EmployeeController::class, 'create'])->name('Employees.create');
    Route::post('/employees/generate-qr', [EmployeeController::class, 'generateQrCode'])->name('employees.generateQrCode');

    // Shift routes
    Route::get('/shift', [ShiftController::class, 'index'])->name('shifts.index');
    Route::post('/shift', [ShiftController::class, 'store'])->name('shifts.store');
    Route::delete('/shift/{id}', [ShiftController::class, 'destroy'])->name('shifts.destroy');
    Route::put('/shift/{id}', [ShiftController::class, 'update'])->name('shifts.update');
    Route::get('/shift/{id}/edit', [ShiftController::class, 'edit'])->name('shifts.edit');


    // Attendance routes
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::post('/attendance/process-qr-code', [AttendanceController::class, 'processQrCode']);
    Route::get('/attendance-records', [AttendanceController::class, 'attendanceRecords'])->name('attendance.records');

   // employee view routes
   Route::get('/employeeview/{id}', [EmployeeController::class, 'viewEmployee'])->name('employeeview');
   //department routes
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('/departments', [DepartmentController::class, 'storeDepartment'])->name('departments.store');
    Route::put('/departments/{id}', [DepartmentController::class, 'updateDepartment'])->name('departments.update');
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroyDepartment'])->name('departments.destroy');

    // Position routes
    Route::get('/positions', [DepartmentController::class, 'index'])->name('positions.index');
    Route::post('/positions', [DepartmentController::class, 'storePosition'])->name('positions.store');
    Route::put('/positions/{id}', [DepartmentController::class, 'updatePosition'])->name('positions.update');
    Route::delete('/positions/{id}', [DepartmentController::class, 'destroyPosition'])->name('positions.destroy');

    // Assign shift route
    // Route::post('/assign-shift', [EmployeeShiftController::class, 'assignShiftToEmployee'])->name('assignShift');
    Route::post('/assign-schedule', [EmployeeschedulesController::class, 'assignSchedule'])->name('assignSchedule');
    Route::delete('/remove-schedules/{employeeId}', [EmployeeschedulesController::class, 'removeSchedules'])->name('removeSchedules');
    Route::delete('/remove-all/{employeeId}', [EmployeeschedulesController::class, 'removeAll'])->name('removeAll');
    // payroll routes
    Route::get('/payroll', function () {
        return view('main.payroll');
    })->name('payroll');

});