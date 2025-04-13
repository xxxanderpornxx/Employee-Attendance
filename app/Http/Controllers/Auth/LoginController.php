<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\empuser;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('Login.login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        // Validate the login credentials
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to find the user by email
        $user = empuser::where('email', $request->email)->first();

        // Check if the user exists and the password matches
        if ($user && Hash::check($request->password, $user->password)) {
            // Log the user in (you can use session or Auth facade)
            session(['user' => $user]);

            // Redirect to the dashboard
            return redirect('/dashboard')->with('success', 'Welcome to the dashboard!');
        }

        // If login fails, redirect back with an error message
        return redirect('/login')->withErrors(['email' => 'Invalid email or password.']);
    }

}
