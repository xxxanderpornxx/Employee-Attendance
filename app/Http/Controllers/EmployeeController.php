<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employees;
use App\Models\EmpPosition;
use App\Models\empuser;
use App\Models\shifts;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch employees and positions
        $employees = Employees::with(['shifts', 'position', 'department'])->get();
        $positions = EmpPosition::all();
        $departments = Department::all();
        $shifts = shifts::all();


        // Pass the data to the view
        return view('main.employee', compact('employees', 'positions', 'departments', 'shifts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show the form to create a new employee
        return view('main.employee', [
            'emppositions' => EmpPosition::all(),
            'departments' => Department::all(),
            'shifts' => shifts::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'FirstName' => 'required|string|max:255',
            'MiddleName' => 'nullable|string|max:255',
            'LastName' => 'required|string|max:255',
            'Sex' => 'required|string|in:Male,Female',
            'DateOfBirth' => 'nullable|date',
            'PositionID' => 'required|exists:emppositions,id',
            'DepartmentID' => 'required|exists:departments,id',
            'ContactNumber' => 'required|string|max:255',
            'Address' => 'nullable|string|max:255',
            'HireDate' => 'required|date',
            'QRcode' => 'nullable|string|max:255',
            'BaseSalary' => 'nullable|numeric|min:0',
            'Email' => 'nullable|email|unique:employees,Email,',
        ]);

        // Create a new employee instance and save it
        $employee = new Employees();
        $employee->fill($validated);
        $employee->save();

        // Generate a QR code for the employee
        $qrcodeString = bin2hex(random_bytes(8));
        $employee->QRcode = $qrcodeString;

        // Generate the QR code image
        $qrcode = QrCode::format('png')->size(200)->generate($qrcodeString);

        // Save the QR code as a file
        $filename = 'qrcodes/employee_' . $employee->id . '.png';
        Storage::disk('public')->put($filename, $qrcode);

        //
        $employee->save();

            empuser::create([
            'name' => $employee->FirstName . ' ' . $employee->LastName, // Combine first and last name
            'email' => $employee->Email,
            'role' => 'Employee', // Default role, you can customize this
            'password' => Hash::make('12345678'), // Hash the default password
        ]);
    return redirect()->route('Employees.index')->with('success', 'Employee created successfully!');
    }

    /**
     * Show the specified resource.
     */
    // public function show(string $id)
    // {
    //     // Fetch the employee by ID
    //     $employee = \App\Models\Employees::with(['position', 'department'])->findOrFail($id);

    //     // Pass the employee to the view
    //     return view('main.employee', compact('employee'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     // Fetch the employee by ID
    //     $employee = \App\Models\Employees::with(['position', 'department'])->findOrFail($id);

    //     // Pass the employee to the edit view
    //     return view('main.employee', compact('employee'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $request->validate([
            'FirstName' => 'required|string|max:255',
            'MiddleName' => 'nullable|string|max:255',
            'LastName' => 'required|string|max:255',
            'Sex' => 'required|string|in:Male,Female',
            'DateOfBirth' => 'nullable|date',
            'PositionID' => 'required|exists:emppositions,id',
            'DepartmentID' => 'required|exists:departments,id',
            'ContactNumber' => 'required|string|max:15',
            'Address' => 'nullable|string|max:255',
            'HireDate' => 'required|date',
            'QRcode' => 'nullable|string|max:255',
            'BaseSalary' => 'required|numeric|min:0',
            'Email' => 'nullable|email|unique:employees,Email,' . $id,
        ]);

        // Find the employee by ID and update their information
        $employee = \App\Models\Employees::findOrFail($id);
        $employee->fill($request->all());
        $employee->save(); // Save the updated employee to the database

    return redirect()->route('Employees.index')->with('success', 'Employee updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the employee by ID and delete them
        $employee = \App\Models\Employees::findOrFail($id);
        $employee->delete();

        // Redirect back with a success message
    return redirect()->route('Employees.index')->with('success', 'Employee deleted successfully!');
    }

    public function generateQrCode(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'employeeId' => 'required|string|max:255', // Ensure employeeId is passed
    ]);

    // Generate a random string for the QR code
    $randomString = bin2hex(random_bytes(8)); // Generate a random 16-character string

    // Generate the QR code
    $qrcode = QrCode::format('png')->size(200)->generate($randomString);

    // Save the QR code as a file
    $filename = 'qrcodes/' . uniqid() . '.png';
    Storage::disk('public')->put($filename, $qrcode);

    // Return the QR code path as a JSON response
    return response()->json(['qrCodePath' => asset('storage/' . $filename)]);
}
        public function viewEmployee($id)
        {
    // Fetch the employee by ID with related department and position
    $employee = Employees::with(['department', 'position'])->findOrFail($id);

    // Pass the employee data to the view
    return view('main.employeeview', compact('employee'));


}

public function profile()
{
    // Use the employee guard instead of web guard
    $authUser = auth()->guard('employee')->user();

    if (!$authUser) {
        return redirect()->route('login')->with('error', 'Please login to access your profile.');
    }

    $employee = Employees::where('Email', $authUser->email)
        ->with(['department', 'position', 'leaveBalance'])
        ->first();

    if (!$employee) {
        return redirect()->route('login')->with('error', 'Employee data not found.');
    }

    return view('employee.profile', compact('employee'));
}
}
