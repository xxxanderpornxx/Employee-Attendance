<?php

namespace App\Http\Controllers;

use App\Models\department;
use App\Models\employees;
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
        $employees = employees::with('position')->get();
        $positions = EmpPosition::all();
        $departments = department::all();

        // Pass the data to the view
        return view('main.employee', compact('employees', 'positions', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show the form to create a new employee
        return view('main.create_employee');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        // Create a new employee record
        \App\Models\employees::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'position_id' => $request->input('position_id'),
            'department_id' => $request->input('department_id'),
        ]);

        // Redirect back with a success message
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(string $id)
    {
        // Fetch the employee by ID
        $employee = \App\Models\employees::with(['position', 'department'])->findOrFail($id);

        // Pass the employee to the view
        return view('main.show_employee', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch the employee by ID
        $employee = \App\Models\employees::with(['position', 'department'])->findOrFail($id);

        // Pass the employee to the edit view
        return view('main.edit_employee', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $id,
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        // Find the employee by ID and update their information
        $employee = \App\Models\employees::findOrFail($id);
        $employee->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'position_id' => $request->input('position_id'),
            'department_id' => $request->input('department_id'),
        ]);

        // Redirect back with a success message
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the employee by ID and delete them
        $employee = \App\Models\employees::findOrFail($id);
        $employee->delete();

        // Redirect back with a success message
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
