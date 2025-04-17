<?php
namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\EmpPosition;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // Fetch all departments and positions
    public function index()
    {
        $departments = Department::all();
        $positions = EmpPosition::all();

        // Pass both variables to the view
        return view('main.position', compact('departments', 'positions'));
    }

    // Store a newly created department
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'DepartmentName' => 'required|string|max:255',
        ]);

        Department::create([
            'DepartmentName' => $request->DepartmentName,
        ]);

        return redirect()->route('departments.index')->with('success', 'Department added successfully.');
    }

    // Update an existing department
    public function updateDepartment(Request $request, $id)
    {
        $request->validate([
            'DepartmentName' => 'required|string|max:255',
        ]);

        $department = Department::findOrFail($id);
        $department->update([
            'DepartmentName' => $request->DepartmentName,
        ]);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    // Delete a department
    public function destroyDepartment($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }

    // Store a newly created position
    public function storePosition(Request $request)
    {
        $request->validate([
            'PositionName' => 'required|string|max:255',
        ]);

        EmpPosition::create([
            'PositionName' => $request->PositionName,
        ]);

        return redirect()->route('departments.index')->with('success', 'Position added successfully.');
    }

    // Update an existing position
    public function updatePosition(Request $request, $id)
    {
        $request->validate([
            'PositionName' => 'required|string|max:255',
        ]);

        $position = EmpPosition::findOrFail($id);
        $position->update([
            'PositionName' => $request->PositionName,
        ]);

        return redirect()->route('departments.index')->with('success', 'Position updated successfully.');
    }

    // Delete a position
    public function destroyPosition($id)
    {
        $position = EmpPosition::findOrFail($id);
        $position->delete();

        return redirect()->route('departments.index')->with('success', 'Position deleted successfully.');
    }
}
