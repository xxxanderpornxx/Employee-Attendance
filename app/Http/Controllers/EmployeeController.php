<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employees;
use App\Models\EmpPosition;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch employees and positions
        $employees = Employees::with(['position', 'department'])->get();
        $positions = EmpPosition::all();
        $departments = Department::all();

        // Pass the data to the view
        return view('main.employee', compact('employees', 'positions', 'departments'));
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
        $employee->save(); // Save the employee to the database

        return redirect()->route('Employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show(string $id)
    {
        // Fetch the employee by ID
        $employee = \App\Models\Employees::with(['position', 'department'])->findOrFail($id);

        // Pass the employee to the view
        return view('main.employee', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the employee by ID
        $employee = \App\Models\Employees::with(['position', 'department'])->findOrFail($id);

        // Pass the employee to the edit view
        return view('main.employee', compact('employee'));
    }

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

        return redirect()->route('Employees.index')->with('success', 'Employee updated successfully.');
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
        return redirect()->route('Employees.index')->with('success', 'Employee deleted successfully.');
    }
}
