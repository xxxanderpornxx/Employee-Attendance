<?php

namespace App\Http\Controllers;

use App\Models\Attendances;
use App\Models\Employees;
use App\Models\overtimerequests;
use App\Models\payrollexports;
use App\Models\leaverequests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index()
    {
        $employees = Employees::all();
        $payrollExports = payrollexports::with('employee')->orderBy('created_at', 'desc')->get();
        return view('main.payroll', compact('employees', 'payrollExports'));
    }

    public function generatePayroll(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'employee_id' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            if ($request->employee_id === 'all') {
                $employees = Employees::all();
                foreach ($employees as $employee) {
                    $summary = $this->calculateAttendanceSummary($employee->id, $startDate, $endDate);

                    payrollexports::create([
                        'EmployeeID' => $employee->id,
                        'PayPeriodStart' => $startDate,
                        'PayPeriodEnd' => $endDate,
                        'BaseSalary' => $employee->BaseSalary,
                        'DaysWorked' => $summary['daysWorked'],
                        'OvertimeHours' => $summary['overtimeHours'],
                        'LeaveDays' => $summary['leaveDays'],
                        'AbsentDays' => $summary['absentDays'],
                        'LateMinutes' => $summary['lateMinutes'],
                        'GrossPay' => $summary['grossPay'],
                        'NetPay' => $summary['netPay'],
                        'ExportDate' => now()
                    ]);
                }
            } else {
                // Generate payroll for a single employee
                $employee = Employees::findOrFail($request->employee_id);
                $summary = $this->calculateAttendanceSummary($employee->id, $startDate, $endDate);

                payrollexports::create([
                    'EmployeeID' => $employee->id,
                    'PayPeriodStart' => $startDate,
                    'PayPeriodEnd' => $endDate,
                    'BaseSalary' => $employee->BaseSalary,
                    'DaysWorked' => $summary['daysWorked'],
                    'OvertimeHours' => $summary['overtimeHours'],
                    'LeaveDays' => $summary['leaveDays'],
                    'AbsentDays' => $summary['absentDays'],
                    'LateMinutes' => $summary['lateMinutes'],
                    'GrossPay' => $summary['grossPay'],
                    'NetPay' => $summary['netPay'],
                    'ExportDate' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payroll generated successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function calculateAttendanceSummary($employeeId, $startDate, $endDate)
    {
        $employee = Employees::findOrFail($employeeId); // Retrieve employee with BaseSalary

        // Fetch attendance records for the employee within the date range
        $attendances = Attendances::where('EmployeeID', $employeeId)
            ->whereBetween('DateTime', [$startDate, $endDate])
            ->get();

        $daysWorked = 0;
        $overtimeHours = 0;
        $leaveDays = 0;
        $absentDays = 0;
        $lateMinutes = 0;

        // Group attendances by date
        $attendancesByDate = $attendances->groupBy(function ($attendance) {
            return $attendance->DateTime->format('Y-m-d');
        });

        foreach ($attendancesByDate as $date => $dailyAttendances) {
            $checkIns = $dailyAttendances->where('Type', 'Check-in')->count();
            $checkOuts = $dailyAttendances->where('Type', 'Check-out')->count();

            // Count a workday if there are at least 2 completed shifts (check-in and check-out)
            if ($checkIns >= 1 && $checkOuts >= 1) {
                $daysWorked++;

                // Calculate late minutes for the first check-in
                $checkIn = $dailyAttendances->where('Type', 'Check-in')->first();
                if ($checkIn && $checkIn->Status === 'Late') {
                    preg_match('/Late by (\d+) minutes/', $checkIn->Remarks, $matches);
                    if (isset($matches[1])) {
                        $lateMinutes += intval($matches[1]);
                    }
                }

                // Calculate overtime hours for the day
                $checkOut = $dailyAttendances->where('Type', 'Check-out')->last();
                if ($checkOut) {
                    $scheduledShiftEnd = $employee->schedules()
                        ->where('DayOfWeek', Carbon::parse($date)->format('l'))
                        ->with('shift')
                        ->first()
                        ->shift
                        ->EndTime;

                    $shiftEnd = Carbon::parse($date . ' ' . $scheduledShiftEnd);
                    $actualCheckOut = Carbon::parse($checkOut->DateTime);

                    if ($actualCheckOut->greaterThan($shiftEnd)) {
                        $overtimeHours += $shiftEnd->diffInHours($actualCheckOut);
                    }
                }
            }
        }

          // Count absent days
            $absentDays = Attendances::where('EmployeeID', $employeeId)
            ->whereBetween('DateTime', [$startDate, $endDate])
            ->where('Status', 'Absent')
            ->count();
        // Include approved leave requests
        $approvedLeaveRequests = leaverequests::where('EmployeeID', $employeeId)
            ->where('Status', 'Approved')
            ->whereBetween('StartDate', [$startDate, $endDate])
            ->get();

        foreach ($approvedLeaveRequests as $leaveRequest) {
            $leaveDays += Carbon::parse($leaveRequest->StartDate)->diffInDays(Carbon::parse($leaveRequest->EndDate)) + 1;
        }

        // Include approved overtime requests
        $approvedOvertimeRequests = overtimerequests::where('EmployeeID', $employeeId)
            ->where('Status', 'Approved')
            ->whereBetween('Date', [$startDate, $endDate])
            ->get();

        foreach ($approvedOvertimeRequests as $overtimeRequest) {
            $startTime = Carbon::parse($overtimeRequest->Date . ' ' . $overtimeRequest->StartTime);
            $endTime = Carbon::parse($overtimeRequest->Date . ' ' . $overtimeRequest->EndTime);
            $overtimeHours += $startTime->diffInHours($endTime);
        }

        // Calculate pay using BaseSalary
        $BaseSalary = $employee->BaseSalary;
        $grossPay = ($daysWorked + $leaveDays) * $BaseSalary; // Include leave days as worked days
        $overtimePay = $overtimeHours * ($BaseSalary / 8); // Formula can be adjusted based on company policy
        $deductions = $lateMinutes * (($BaseSalary / 8) / 60); // Can be adjusted based on company policy
        $absentDeductions = $absentDays * $BaseSalary;
        $netPay = $grossPay + $overtimePay - $deductions - $absentDeductions;

        return [
            'daysWorked' => $daysWorked,
            'overtimeHours' => $overtimeHours,
            'leaveDays' => $leaveDays,
            'absentDays' => $absentDays,
            'lateMinutes' => $lateMinutes,
            'grossPay' => $grossPay,
            'netPay' => $netPay
        ];
    }
}