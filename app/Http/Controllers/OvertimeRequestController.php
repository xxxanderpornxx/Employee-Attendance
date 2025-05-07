<?php


namespace App\Http\Controllers;

use App\Models\overtimerequests;
use App\Models\Employees;
use App\Models\leaverequests;
use Illuminate\Http\Request;

class OvertimeRequestController extends Controller
{
public function store(Request $request)
{
    $validated = $request->validate([
        'Date' => 'required|date',
        'StartTime' => 'required',
        'EndTime' => 'required|after:StartTime'
    ]);

    $empuser = auth()->guard('employee')->user();
    $employee = Employees::where('Email', $empuser->email)->first();

    if (!$employee) {
        return redirect()->back()
            ->with('error', 'Employee record not found.')
            ->withInput();
    }

    $overtimeRequest = new overtimerequests();
    $overtimeRequest->EmployeeID = $employee->id;
    $overtimeRequest->Date = $validated['Date'];
    $overtimeRequest->StartTime = $validated['StartTime'];
    $overtimeRequest->EndTime = $validated['EndTime'];
    $overtimeRequest->Status = 'Pending';
    $overtimeRequest->save();

    return redirect()->route('employee.employeeleaverequest')
        ->with('success', 'Overtime request submitted successfully.');
}
    public function index()
{
    $overtimeRequests = overtimerequests::with('employee')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('main.overtimerequests', compact('overtimeRequests'));
}

public function updateStatus(Request $request, $id)
{
    $validated = $request->validate([
        'status' => 'required|in:Approved,Rejected'
    ]);

    $overtimeRequest = overtimerequests::findOrFail($id);
    $overtimeRequest->Status = $validated['status'];
    $overtimeRequest->save();

    return redirect()->route('overtimerequests.index')
        ->with('success', 'Overtime request status updated successfully.');
}
public function showLeaveRequests()
{
    $empuser = auth()->guard('employee')->user();
    $employee = Employees::where('Email', $empuser->email)->first();

    if (!$employee) {
        return redirect()->back()->with('error', 'Employee record not found.');
    }

    // Fetch overtime and leave requests for the logged-in employee
    $overtimeRequests = overtimerequests::where('EmployeeID', $employee->id)
        ->orderBy('created_at', 'desc')
        ->get();

    $leaveRequests = leaverequests::where('EmployeeID', $employee->id)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('employee.employeeleaverequest', compact('overtimeRequests', 'leaveRequests'));
}
}