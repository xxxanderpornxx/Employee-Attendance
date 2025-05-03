<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeSchedules;
use App\Models\Employees;
use App\Models\Shifts;

class EmployeeschedulesController extends Controller
{


    public function assignSchedule(Request $request)
    {
        $validated = $request->validate([
            'EmployeeID' => 'required|exists:employees,id',
            'ShiftIDs' => 'required|array|min:1|max:2', // Allow 1 or 2 shifts
            'ShiftIDs.*' => 'exists:shifts,id',
            'Days' => 'required|array', // Days for each shift
            'Days.*' => 'array|min:1', // At least one day must be selected for each shift
            'Days.*.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ]);

        // Remove existing schedules for the employee
        EmployeeSchedules::where('EmployeeID', $validated['EmployeeID'])->delete();

        // Assign new schedules
        foreach ($validated['ShiftIDs'] as $shiftKey => $shiftId) {
            if (isset($validated['Days'][$shiftKey])) {
                foreach ($validated['Days'][$shiftKey] as $day) {
                    EmployeeSchedules::create([
                        'EmployeeID' => $validated['EmployeeID'],
                        'ShiftID' => $shiftId,
                        'DayOfWeek' => $day,
                    ]);
                }
            }
        }
    // Sync the shifts in the employeeshifts table
    $employee = Employees::findOrFail($validated['EmployeeID']);
    $employee->shifts()->sync($validated['ShiftIDs']); // Sync shifts for the employee

    return redirect()->back()->with('success', 'Schedules and shifts assigned successfully.');
}
    public function removeAll(Request $request, $employeeId)
    {
        // Validate that the employee exists
        $employee = Employees::findOrFail($employeeId);

        // Remove all schedules for the employee
        EmployeeSchedules::where('EmployeeID', $employeeId)->delete();

        // Detach all shifts for the employee
        $employee->shifts()->detach();

        return redirect()->back()->with('success', 'All schedules and shifts removed successfully.');
    }
}