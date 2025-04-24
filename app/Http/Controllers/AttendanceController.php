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
            $attendances = Attendances::with('employee')->get();
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


                // Find the employee by QR code
                $employee = Employees::where('QRcode', $qrCode)->first();
                if (!$employee) {
                    return response()->json(['message' => 'Employee not found.'], 404);
                }

                // Check the last attendance record for this employee
                $lastAttendance = Attendances::where('EmployeeID', $employee->id)
                    ->orderBy('DateTime', 'desc')
                    ->first();

                $type = $lastAttendance && $lastAttendance->Type === 'Check-in' ? 'Check-out' : 'Check-in';

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
    }
