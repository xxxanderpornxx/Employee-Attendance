<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\empuser; // Use the empuser model
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
public function showLogin() {
    // Check if the user is authenticated
    if (Auth::check()) {
        $user = Auth::user();

        // Redirect based on role
        if ($user->role === 'employee') {
            return redirect()->route('employee.profile');
        }

        if ($user->role === 'admin') {
            return redirect()->route('main.dashboard');
        }
    }

    // If not authenticated, show the login page
    return view('Login.login'); // Ensure the path matches your file structure
}

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:empusers,email', // Validate against empusers table
            'password' => 'required|min:6|confirmed', // Ensure password confirmation
            'role' => 'required|in:admin,manager,hr,employee', // Validate role field
        ]);

        empuser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
            'role' => $request->role,
        ]);

        return redirect('/login')->with('success', 'Registered successfully. Please login.');
    }
    public function showRegister() {
            if (Auth::check()) {
        $user = Auth::user();

        // Redirect based on role
        if ($user->role === 'employee') {
            return redirect()->route('employee.profile');
        }

        if ($user->role === 'admin') {
            return redirect()->route('main.dashboard');
        }
    }
        return view('Login.register'); // Adjust the path to match your file structure
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate using the employee guard
        if (auth()->guard('employee')->attempt($credentials)) {
            $user = auth()->guard('employee')->user();

            // Redirect based on role
            if ($user->role === 'employee') {
                return redirect()->route('employee.profile');
            }

            if ($user->role === 'admin') {
                return redirect()->route('main.dashboard');
            }
        }

           // If authentication fails, flash an error message
    return view('Login.login'); // Correct the path to match your file structure
    }


    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
