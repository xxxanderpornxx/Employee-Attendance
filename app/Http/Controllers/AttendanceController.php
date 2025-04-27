<?php
namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\Attendances;
    use App\Models\Employees;
    class AttendanceController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index()
        {
                 $today = now()->toDateString();

                // Fetch attendance logs for today only
                $attendances = Attendances::with('employee')
                    ->whereDate('DateTime', $today)
                    ->get();

                return view('main.attendance', compact('attendances'));
        }

        /**
         * Show the form for creating a new resource.
         */
        public function create()
        {
            //
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(Request $request)
        {
            //
        }

        /**
         * Display the specified resource.
         */
        public function show(string $id)
        {
            //
        }

        /**
         * Show the form for editing the specified resource.
         */
        public function edit(string $id)
        {
            //
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, string $id)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(string $id)
        {
            //
        }

        public function processQrCode(Request $request)
        {
            try {
                $qrCode = $request->input('qrCode');

                // Validate QR code input
                if (!$qrCode) {
                    return response()->json(['message' => 'QR code is required.'], 400);
                }

                // Find the employee by QR code
                $employee = Employees::where('QRcode', $qrCode)->first();
                if (!$employee) {
                    return response()->json(['message' => 'Employee not found.'], 404);
                }

                // Get the employee's assigned shift through Employeeshifts
                $employeeShift = $employee->employeeshift;
                if (!$employeeShift) {
                    return response()->json(['message' => 'No shift assigned to this employee.'], 404);
                }

                $shift = $employeeShift->shift;
                if (!$shift) {
                    return response()->json(['message' => 'Shift details not found.'], 404);
                }

                // Parse shift start and end times
                $shiftStart = now()->setTimeFromTimeString($shift->StartTime); // e.g., 08:00
                $shiftEnd = now()->setTimeFromTimeString($shift->EndTime);     // e.g., 12:00

                // Check the last attendance record for this employee
                $lastAttendance = Attendances::where('EmployeeID', $employee->id)
                    ->orderBy('DateTime', 'desc')
                    ->first();

                $type = $lastAttendance && $lastAttendance->Type === 'Check-in' ? 'Check-out' : 'Check-in';

                // Validate check-out times based on the shift
                if ($type === 'Check-out') {
                    if (now()->lessThan($shiftEnd)) {
                        return response()->json(['message' => 'You are checking out too early.'], 400);
                    }
                }

                // Create a new attendance record
                $attendance = Attendances::create([
                    'EmployeeID' => $employee->id,
                    'Type' => $type,
                    'DateTime' => now(),
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
        public function attendanceRecords()
        {
            $attendances = Attendances::with('employee')
                ->get()
                ->map(function ($attendance) {
                    return [
                        'id' => $attendance->id,
                        'employee_name' => $attendance->employee->FirstName . ' ' . $attendance->employee->LastName,
                        'type' => $attendance->Type,
                        'date_time' => $attendance->DateTime->timezone('Asia/Manila')->format('Y-m-d | h:i A'),
                    ];
                });

            return view('main.attendancerecord', ['attendances' => $attendances]);
        }
 }
