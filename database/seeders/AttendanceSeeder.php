<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendances;
use App\Models\Employees;
use App\Http\Controllers\EmpleavebalanceController;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        // Seed attendance records for existing employees
        Attendances::insert([
            // Attendance for EmployeeID = 2
            ['EmployeeID' => 8	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-11 08:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 8	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-11 12:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 8	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-11 13:30:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 8	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-11 17:30:00', 'Remarks'=> 'On time'],



        ]);

        // Process attendance records to update leave balances for each employee
        $employeeIds = [8]; // IDs of existing employees
        $today = '2025-05-11';

        foreach ($employeeIds as $employeeId) {
            $employee = Employees::find($employeeId);

            $completedShifts = Attendances::where('EmployeeID', $employee->id)
                ->whereDate('DateTime', $today)
                ->where('Type', 'Check-out')
                ->count();

            if ($completedShifts === 2) {
                $daysWorked = 1; // 1 full day of work
                $leaveCredits = app(EmpleavebalanceController::class)->getLeaveCredits($daysWorked);

                $leaveBalance = $employee->leaveBalance;
                if ($leaveBalance) {
                    $leaveBalance->increment('VacationLeave', $leaveCredits['VacationLeave']);
                    $leaveBalance->increment('SickLeave', $leaveCredits['SickLeave']);
                } else {
                    $employee->leaveBalance()->create([
                        'VacationLeave' => $leaveCredits['VacationLeave'],
                        'SickLeave' => $leaveCredits['SickLeave'],
                    ]);
                }
            }
        }
    }
}
