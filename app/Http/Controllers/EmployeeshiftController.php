<?php

namespace App\Http\Controllers;
use App\Models\Employeeshifts;
use Illuminate\Http\Request;

class EmployeeshiftController extends Controller
{
    public function assignShiftToEmployee(Request $request)
    {
        $validated = $request->validate([
            'EmployeeID' => 'required|exists:employees,id',
            'ShiftIDs' => 'required|array|min:2|max:2',
            'ShiftIDs.*' => 'required|exists:shifts,id',
        ]);

        // Remove any existing shifts for the employee on the given date
        Employeeshifts::where('EmployeeID', $validated['EmployeeID'])
            ->delete();

        // Assign the two shifts
        foreach ($validated['ShiftIDs'] as $shiftId) {
            $employeeshift = new Employeeshifts();
            $employeeshift->EmployeeID = $validated['EmployeeID'];
            $employeeshift->ShiftID = $shiftId;
            $employeeshift->save();
        }

        return redirect()->back()->with('success', 'Shifts assigned successfully!');
    }

    public function assignShift(Request $request)
    {
        $validated = $request->validate([
            'EmployeeID' => 'required|exists:employees,id',
            'ShiftID' => 'required|exists:shifts,id',
        ]);

        // Create or update the employee's shift
        Employeeshifts::updateOrCreate(
            ['EmployeeID' => $validated['EmployeeID']],
            ['ShiftID' => $validated['ShiftID']]
        );

        return redirect()->back()->with('success', 'Shift assigned successfully!');
    }
}
