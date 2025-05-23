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
            ['EmployeeID' => 1	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 1	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 1	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14  00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 1	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],

           ['EmployeeID' => 2	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 2	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 2	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14  00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 2	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],

           ['EmployeeID' => 3	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 3	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 3	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 3	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],

            ['EmployeeID' => 4	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 4	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 4	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 4	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],

            ['EmployeeID' => 5	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 5	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 5	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 5	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],

            ['EmployeeID' => 7	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 7	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 7	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 7	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],

            // ['EmployeeID' => 8	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            // ['EmployeeID' => 8	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],
            // ['EmployeeID' => 8	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            // ['EmployeeID' => 8	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],

            // ['EmployeeID' => 11	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            // ['EmployeeID' => 11	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],
            // ['EmployeeID' => 11	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            // ['EmployeeID' => 11	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],

            ['EmployeeID' => 15	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 15	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 15	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 00:00:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 15	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 23:59:00', 'Remarks'=> 'On time'],

            ['EmployeeID' => 16	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 13:30:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 16	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 15:30:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 16	  , 'Type' => 'Check-in', 'DateTime' => '2025-05-14 13:30:00', 'Remarks'=> 'On time'],
            ['EmployeeID' => 16	  , 'Type' => 'Check-out', 'DateTime' => '2025-05-14 15:30:00', 'Remarks'=> 'On time'],



        ]);

        // Process attendance records to update leave balances for each employee
        $employeeIds = [1, 2, 3, 4, 5, 7, 8, 11, 15, 16]; // IDs of existing employees
        $today = '2025-05-14';

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