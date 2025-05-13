<?php
namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Attendances;
    use App\Models\Employees;
    use Carbon\Carbon;
    class AttendanceController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index()
        {      $today = now()->format('Y-m-d');

            $attendances = Attendances::with('employee')
                ->whereDate('DateTime', $today) // Filter by today's date
                ->orderBy('DateTime', 'desc') // Order by most recent first
                ->get();
                return view('main.attendance', compact('attendances'));
        }


        public function processQrCode(Request $request)
        {
            try {
                $qrCode = $request->input('qrCode');

                // Find the employee by QR code
                $employee = Employees::where('QRcode', $qrCode)->first();
                if (!$employee) {
                    return response()->json(['message' => 'Employee not found.'], 404);
                }

                // Get today's day of the week
                $todayDayOfWeek = now()->format('l'); // e.g., "Sunday"

                // Check if the employee has a schedule for today
                $todaySchedules = $employee->schedules()->where('DayOfWeek', $todayDayOfWeek)->with('shift')->get();
                if ($todaySchedules->isEmpty()) {
                    return response()->json(['message' => 'No schedule for today.'], 400);
                }

                // Get the current time
                $currentTime = now();

                // Check if the employee is within their scheduled shifts
                $validShift = null;
                foreach ($todaySchedules as $schedule) {
                    $shiftStart = now()->setTimeFromTimeString($schedule->shift->StartTime);
                    $shiftEnd = now()->setTimeFromTimeString($schedule->shift->EndTime);

                    // Handle shifts that span across midnight
                    if ($shiftEnd->lessThan($shiftStart)) {
                        // Shift spans across midnight
                        if ($currentTime->greaterThanOrEqualTo($shiftStart) || $currentTime->lessThanOrEqualTo($shiftEnd)) {
                            $validShift = $schedule->shift;
                            break;
                        }
                    } else {
                        $earlyCheckInWindow = $shiftStart->copy()->subMinutes(30);

                        if ($currentTime->between($earlyCheckInWindow, $shiftEnd)) {
                            $validShift = $schedule->shift;
                            break;
                        }
                    }
                }

                if (!$validShift) {
                    return response()->json(['message' => 'You are not within your scheduled shift time.'], 400);
                }

                // Check the last attendance record for this employee
                $today = now()->format('Y-m-d');
                $lastAttendance = Attendances::where('EmployeeID', $employee->id)
                    ->whereDate('DateTime', $today)
                    ->orderBy('DateTime', 'desc')
                    ->first();

                // Determine the type of attendance (Check-in or Check-out)
                $type = $lastAttendance && $lastAttendance->Type === 'Check-in' ? 'Check-out' : 'Check-in';


                $status = 'Present';
                $remarks = null;

                if ($type === 'Check-in') {
                    // Make sure both times are Carbon instances without seconds/microseconds
                    $currentTime = Carbon::now()->seconds(0); // force zero seconds
                    $shiftStart = Carbon::parse($validShift->StartTime)->seconds(0);

                    // Calculate difference (can be negative if early)
                    $differenceInMinutes = $shiftStart->diffInRealMinutes($currentTime, false);

                    if ($differenceInMinutes < 0) {
                        $earlyMinutes = abs($differenceInMinutes);
                        if ($earlyMinutes <= 15) {
                            $status = 'Present';
                            $remarks = "Checked in " . intval($earlyMinutes) . " minutes early";
                        } else {
                            $status = 'Early';
                            $remarks = "Checked in too early by " . intval($earlyMinutes) . " minutes";
                        }
                    } elseif ($differenceInMinutes > 0) {
                        if ($differenceInMinutes <= 15) {
                            $status = 'Present';
                            $remarks = "Late by " . intval($differenceInMinutes) . " minutes";
                        } else {
                            $status = 'Late';
                            $remarks = "Late by " . intval($differenceInMinutes) . " minutes";
                        }
                    } else {
                        $status = 'Present';
                        $remarks = "Checked in on time";
                    }
                }


                // Prevent multiple check-ins or check-outs for the same shift
                if ($type === 'Check-in') {
                    $alreadyCheckedIn = Attendances::where('EmployeeID', $employee->id)
                        ->whereDate('DateTime', $today)
                        ->where('Type', 'Check-in')
                        ->whereTime('DateTime', '>=', $validShift->StartTime)
                        ->whereTime('DateTime', '<=', $validShift->EndTime)
                        ->exists();

                    if ($alreadyCheckedIn) {
                        return response()->json(['message' => 'You have already checked in for this shift.'], 400);
                    }
                } elseif ($type === 'Check-out') {
                    $alreadyCheckedOut = Attendances::where('EmployeeID', $employee->id)
                        ->whereDate('DateTime', $today)
                        ->where('Type', 'Check-out')
                        ->whereTime('DateTime', '>=', $validShift->StartTime)
                        ->whereTime('DateTime', '<=', $validShift->EndTime)
                        ->exists();

                    if ($alreadyCheckedOut) {
                        return response()->json(['message' => 'You have already checked out for this shift.'], 400);
                    }

                    // Prevent check-out before the shift ends
                    $shiftEnd = now()->setTimeFromTimeString($validShift->EndTime);
                    if ($currentTime->lessThan($shiftEnd)) {
                        return response()->json(['message' => 'You are checking out too early.'], 400);
                    }
                }

                // Check if the employee has completed both shifts for the day
                    if ($type === 'Check-out') {
                        $completedShifts = Attendances::where('EmployeeID', $employee->id)
                            ->whereDate('DateTime', $today)
                            ->where('Type', 'Check-out')
                            ->count();

                        if ($completedShifts === 2) {
                            // Calculate leave credits for the completed day
                            $daysWorked = 1; // 1 full day of work
                            $leaveCredits = app(EmpleavebalanceController::class)->getLeaveCredits($daysWorked);

                            // Update the employee's leave balances
                            $leaveBalance = $employee->leaveBalance; // Assuming a relationship exists
                            if ($leaveBalance) {
                                $leaveBalance->increment('VacationLeave', $leaveCredits['VacationLeave']);
                                $leaveBalance->increment('SickLeave', $leaveCredits['SickLeave']);
                            } else {
                                // Create a new leave balance record if it doesn't exist
                                $employee->leaveBalance()->create([
                                    'VacationLeave' => $leaveCredits['VacationLeave'],
                                    'SickLeave' => $leaveCredits['SickLeave'],
                                ]);
                            }
                        }
                    }

                // Create a new attendance record
                $attendance = Attendances::create([
                    'EmployeeID' => $employee->id,
                    'Type' => $type,
                    'Status' => $status,
                    'Remarks' => $remarks,
                    'DateTime' => $currentTime,
                ]);

                return response()->json([
                    'message' => "Successfully recorded $type.",
                    'attendance' => $attendance,
                    'employee' => $employee,
                ]);
            } catch (\Exception $e) {
                return response()->json(['message' => 'An error occurred.', 'error' => $e->getMessage()], 500);
            }
        }

        public function attendanceRecords(Request $request)
        {
            // Check if a specific employee is selected
            $employeeId = $request->has('EmployeeID') ? $request->input('EmployeeID') : null;

            if ($employeeId) {
                // Fetch attendance records for the specific employee
                $attendances = Attendances::with('employee')
                    ->where('EmployeeID', $employeeId)
                    ->get()
                    ->map(function ($attendance) {
                        return [
                            'id' => $attendance->id,
                            'EmployeeID' => $attendance->EmployeeID, // Include EmployeeID explicitly
                            'employee_name' => $attendance->employee->FirstName . ' ' . $attendance->employee->LastName,
                            'type' => $attendance->Type,
                            'status' =>$attendance -> Status,
                            'remarks' => $attendance->Remarks,
                            'date_time' => $attendance->DateTime->timezone('Asia/Manila')->format('Y-m-d | h:i A'),
                        ];
                    });
            } else {
                $attendances = Attendances::with('employee')
                    ->get()
                    ->map(function ($attendance) {
                        return [
                            'id' => $attendance->id,
                            'EmployeeID' => $attendance->EmployeeID,
                            'employee_name' => $attendance->employee->FirstName . ' ' . $attendance->employee->LastName,
                            'type' => $attendance->Type,
                            'status' => $attendance->Status,
                            'remarks' => $attendance->Remarks,
                            'date_time' => $attendance->DateTime->timezone('Asia/Manila')->format('Y-m-d | h:i A'),
                        ];
                    });
            }

            // Fetch all employees for the dropdown
            $employees = Employees::all();

            return view('main.attendancerecord', [
                'attendances' => $attendances,
                'employees' => $employees,
                'selectedEmployeeId' => $employeeId, // Pass the selected employee ID to the view
            ]);
        }
        public function getEmployeeAttendanceRecords()
        {
            $empuser = auth()->guard('employee')->user();

            if (!$empuser) {
                abort(403, 'Unauthorized access');
            }

            // Get the corresponding employee record using the email
            $employee = Employees::where('Email', $empuser->email)->first();

            if (!$employee) {
                abort(404, 'Employee record not found');
            }

            // Fetch attendance records for the authenticated employee
            $attendanceLogs = Attendances::where('EmployeeID', $employee->id)
                ->orderBy('DateTime', 'desc')
                ->get();

            // Map the attendance logs for the view
            $mappedLogs = $attendanceLogs->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'type' => $attendance->Type,
                    'status' => $attendance->Status,
                    'remarks' => $attendance->Remarks,
                    'date_time' => $attendance->DateTime->format('Y-m-d | h:i A'),
                ];
            });



            return view('employee.attendancerecords', compact('mappedLogs'));
        }

    // Mark absent employees who have no check-in record for today
public function markAbsentEmployees()
{
    try {
        $today = Carbon::now()->format('Y-m-d');
        $dayOfWeek = Carbon::now()->format('l');
        $currentTime = Carbon::now();
        $markedCount = 0;



        // Get all employees scheduled for today
        $employees = Employees::whereHas('schedules', function($query) use ($dayOfWeek) {
            $query->where('DayOfWeek', $dayOfWeek);
        })->get();

        foreach ($employees as $employee) {
            // Check if there's any check-in record for today
            $hasCheckin = Attendances::where('EmployeeID', $employee->id)
                ->whereDate('DateTime', $today)
                ->where('Type', 'Check-in')
                ->exists();

            // Check if already marked absent
            $alreadyMarkedAbsent = Attendances::where('EmployeeID', $employee->id)
                ->whereDate('DateTime', $today)
                ->where('Type', 'Auto-marked')
                ->where('Status', 'Absent')
                ->exists();

            // If no check-in and not already marked absent, mark as absent
            if (!$hasCheckin && !$alreadyMarkedAbsent) {
                Attendances::create([
                    'EmployeeID' => $employee->id,
                    'Type' => 'Auto-marked',
                    'Status' => 'Absent',
                    'Remarks' => 'Auto-marked absent - No check-in record for the day',
                    'DateTime' => Carbon::now()->endOfDay(),
                ]);

                $markedCount++;
            }
        }

        $message = $markedCount > 0
            ? "Successfully marked {$markedCount} employees as absent."
            : "No employees to mark as absent.";

        return [
            'success' => true,
            'message' => $message,
            'marked_count' => $markedCount
        ];

    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => "Error marking absent employees: " . $e->getMessage()
        ];
    }
}
    }
