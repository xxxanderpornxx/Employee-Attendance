<?php
namespace App\Http\Controllers;

use App\Models\EmpPosition;
use App\Models\Department;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = EmpPosition::all();

        return view('main.position', compact('positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'PositionName' => 'required|string|max:255',
        ]);

        EmpPosition::create([
            'PositionName' => $request->input('PositionName'),
        ]);

        return redirect()->route('positions.index')->with('success', 'Position created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'PositionName' => 'required|string|max:255',
        ]);

        $position = EmpPosition::findOrFail($id);
        $position->update([
            'PositionName' => $request->PositionName,
        ]);

        return redirect()->route('positions.index')->with('success', 'Position updated successfully!');
    }

    public function destroy($id)
    {
        $position = EmpPosition::findOrFail($id);
        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Position deleted successfully!');
    }
}

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

    public function store(Request $request)
    {
        $request->validate([
            'PositionName' => 'required|string|max:255',
        ]);

        EmpPosition::create([
            'PositionName' => $request->input('PositionName'),
        ]);

        return redirect()->route('positions.index')->with('success', 'Position created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'PositionName' => 'required|string|max:255',
        ]);

        $position = EmpPosition::findOrFail($id);
        $position->update([
            'PositionName' => $request->PositionName,
        ]);

        return redirect()->route('positions.index')->with('success', 'Position updated successfully!');
    }

    public function destroy($id)
    {
        $position = EmpPosition::findOrFail($id);
        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Position deleted successfully!');
    }
}
