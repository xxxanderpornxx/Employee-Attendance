<?php

namespace App\Http\Controllers;

use App\Models\empleavebalances;
use App\Models\leaverequests;
use App\Models\Employees;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller

{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'StartDate' => 'required|date',
            'EndDate' => 'required|date|after_or_equal:StartDate',
            'LeaveType' => 'required|in:Sick Leave,Vacation Leave',
            'Reason' => 'required|string',
        ]);

        $empuser = auth()->guard('employee')->user();
        // Get the corresponding employee record using the email
        $employee = Employees::where('Email', $empuser->email)->first();

        if (!$employee) {
            return redirect()->back()
                ->with('error', 'Employee record not found.')
                ->withInput();
        }

        $leaveRequest = new leaverequests();
        $leaveRequest->EmployeeID = $employee->id; // Use the employee ID instead of auth user ID
        $leaveRequest->StartDate = $validated['StartDate'];
        $leaveRequest->EndDate = $validated['EndDate'];
        $leaveRequest->LeaveType = $validated['LeaveType'];
        $leaveRequest->Reason = $validated['Reason'];
        $leaveRequest->save();

        return redirect()->route('employee.employeeleaverequest')
            ->with('success', 'Leave request submitted successfully.');
    }
    public function index()
    {
        $user = auth()->guard('employee')->user();

        // Check if user is admin or employee
        if ($user->role === 'admin') {
            $leaveRequests = leaverequests::with('employee')
                ->orderBy('created_at', 'desc')
                ->get();
            return view('main.leaverequest', compact('leaveRequests'));
        } else {
            // For employees, only show their own requests
            $employee = Employees::where('Email', $user->email)->first();

            if (!$employee) {
                return redirect()->back()->with('error', 'Employee record not found.');
            }

            $leaveRequests = leaverequests::where('EmployeeID', $employee->id)
                ->orderBy('created_at', 'desc')
                ->get();
            return view('employee.employeeleaverequest', compact('leaveRequests'));
        }
    }


    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Approved,Denied',
        ]);

        $leaveRequest = leaverequests::findOrFail($id);

        // If the status is being updated to "Approved"
        if ($validated['status'] === 'Approved') {
            $employeeId = $leaveRequest->EmployeeID;

            // Fetch the employee's leave balance
            $leaveBalance = empleavebalances::where('EmployeeID', $employeeId)->first();

            if (!$leaveBalance) {
                return redirect()->back()->with('error', 'Leave balance record not found.');
            }

            // Calculate the number of leave days
            $leaveDays = Carbon::parse($leaveRequest->StartDate)->diffInDays(Carbon::parse($leaveRequest->EndDate)) + 1;

            // Deduct from the appropriate leave balance
            if ($leaveRequest->LeaveType === 'Vacation Leave') {
                $leaveBalance->VacationLeave -= $leaveDays;
            } elseif ($leaveRequest->LeaveType === 'Sick Leave') {
                $leaveBalance->SickLeave -= $leaveDays;
            }

            // Ensure the balance does not go below zero
            $leaveBalance->VacationLeave = max(0, $leaveBalance->VacationLeave);
            $leaveBalance->SickLeave = max(0, $leaveBalance->SickLeave);

            // Save the updated leave balance
            $leaveBalance->save();
        }

        // Update the leave request status
        $leaveRequest->Status = $validated['status'];
        $leaveRequest->save();

        return redirect()->route('leaverequests.index')
            ->with('success', 'Leave request status updated successfully.');
    }
}
