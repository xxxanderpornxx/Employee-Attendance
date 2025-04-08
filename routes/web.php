<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\empuser;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

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

// Route to show the employee

// Dashboard route
Route::get('/dashboard', function () {
    return view('main.dashboard'); // Replace with your actual dashboard view
})->name('dashboard');

// Attendance route
Route::get('/attendance', function () {
    return view('main.attendance'); // Replace with your actual attendance view
})->name('attendance');

// Employee route
Route::get('/employee', function () {
    return view('main.employee'); // Replace with your actual employee view
})->name('employee');

// Shifts route
Route::get('/shift', function () {
    return view('main.shift'); // Replace with your actual shift view
})->name('shift');

// Payroll route
Route::get('/payroll', function () {
    return view('main.payroll'); // Replace with your actual payroll view
})->name('payroll');
