<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendances; // Import the Attendances model
use App\Models\Employees; // Import the Employees model

class AttendanceController extends Controller
{
    public function index()
    {
        // Fetch attendance records from the database with the related employee
        $attendances = Attendances::with('employees')->get();

        // Pass the $attendances variable to the view
        return view('main.attendance', compact('attendances'));
    }

    public function processQRCode(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'qrCode' => 'required|string',
    ]);

    // Extract the QR code from the request
    $qrCode = $validated['qrCode'];

    // Log the QR code for debugging
    \Log::info('Processing QR Code:', ['qrCode' => $qrCode]);

    // Find the employee by QR code
    $employee = Employees::where('QRcode', $qrCode)->first();

    if (!$employee) {
        // Log if the employee is not found
        \Log::warning('Employee not found for QR code:', ['qrCode' => $qrCode]);
        return response()->json(['message' => 'Employee not found.'], 404);
    }

    // Log the employee found
    \Log::info('Employee found:', ['employee_id' => $employee->id, 'name' => $employee->FirstName . ' ' . $employee->LastName]);

    // Check if the employee already has a "Check-in" record for today
    $existingAttendance = Attendances::where('EmployeeID', $employee->id)
        ->whereDate('DateTime', now()->toDateString())
        ->where('Type', 'Check-in')
        ->first();

    if ($existingAttendance) {
        // Log if the employee already checked in
        \Log::info('Employee already checked in today:', ['employee_id' => $employee->id]);
        return response()->json(['message' => 'Employee already checked in today.'], 200);
    }

    // Store the attendance record
    $attendance = Attendances::create([
        'EmployeeID' => $employee->id,
        'Type' => 'Check-in', // Default to 'Check-in'
        'DateTime' => now(),
    ]);

    // Log the attendance record created
    \Log::info('Attendance record created:', $attendance->toArray());

    return response()->json([
        'message' => 'Attendance marked successfully!',
        'attendance' => $attendance,
        'employee' => $employee,
    ]);
}
}
