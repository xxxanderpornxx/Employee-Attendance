<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\empuser;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ShiftsController;

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
Route::get('/employee', function () {
    return view('main.employee');
})->name('employee');

// Shifts route
Route::get('/shift', function () {
    return view('main.shift');
})->name('shift');

Route::get('/shift', [ShiftsController::class, 'index'])->name('shifts.index');
Route::post('/shift', [ShiftsController::class, 'store'])->name('shifts.store');
Route::delete('/shift/{id}', [ShiftsController::class, 'destroy'])->name('shifts.destroy');
Route::put('/shift/{id}', [ShiftsController::class, 'update'])->name('shifts.update');
Route::get('/shift/{id}/edit', [ShiftsController::class, 'edit'])->name('shifts.edit');

// Payroll route
Route::get('/payroll', function () {
    return view('main.payroll');
})->name('payroll');

//position route
Route::get('/positions', function () {
    return view('main.position');
})->name('position');
Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
Route::post('/positions', [PositionController::class, 'store'])->name('positions.store');
Route::put('/positions/{id}', [PositionController::class, 'update'])->name('positions.update');
Route::delete('/positions/{id}', [PositionController::class, 'destroy'])->name('positions.destroy');
