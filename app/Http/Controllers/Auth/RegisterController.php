<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\empuser;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('Login.register');
    }

    /**
     * Handle the registration request.
     */
    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:empusers,email',
            'role' => 'required|in:HR,Manager,Admin',
            'password' => 'required|min:8',
        ]);

        // Create the empuser using the model method
        empuser::createEmpuser($request->all());

        // Redirect to the login page with a success message
        return redirect('/login')->with('success', 'Registration successful! Please log in.');
    }
}
