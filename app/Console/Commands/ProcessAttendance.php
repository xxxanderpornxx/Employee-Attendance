<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendances;
use App\Models\Employees;
use App\Http\Controllers\EmpleavebalanceController;

class ProcessAttendance extends Command
{
    protected $signature = 'attendance:process';
    protected $description = 'Process attendance records and update leave balances';

    public function handle()
    {
        $employees = Employees::all();

        foreach ($employees as $employee) {
            $today = now()->format('Y-m-d');
            $completedShifts = Attendances::where('EmployeeID', $employee->id)
                ->whereDate('DateTime', $today)
                ->where('Type', 'Check-out')
                ->count();

            if ($completedShifts === 2) {
                $daysWorked = 1;
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

        $this->info('Attendance records processed successfully.');
    }
}