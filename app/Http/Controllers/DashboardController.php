<?php
namespace App\Http\Controllers;

use App\Models\Attendances;
use App\Models\Employees;
use App\Models\leaverequests;
use App\Models\overtimerequests;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employees::count();
      // Calculate today's attendance
        $today = Carbon::today();
        $totalAttendance = Attendances::whereDate('DateTime', $today)
        ->where('Type', 'Check-in')
        ->distinct('EmployeeID')
        ->count();

        //pending requests
         $pendingLeaveRequests = leaverequests::where('Status', 'Pending')->count();
        $pendingOvertimeRequests = overtimerequests::where('Status', 'Pending')->count();

         // Calculate days until next payroll (end of the month)
        $endOfMonth = Carbon::now()->endOfMonth();
        $daysUntilNextPayroll = $today->diffInDays($endOfMonth, false);
        $daysUntilNextPayroll = abs((int)$daysUntilNextPayroll);

          // Calculate attendance trends for the past 30 days
        $attendanceTrend = Attendances::selectRaw('DATE(DateTime) as date, COUNT(DISTINCT EmployeeID) as count')
            ->whereBetween('DateTime', [Carbon::now()->subDays(30), Carbon::now()])
            ->where('Type', 'Check-in')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

    return view('main.dashboard', compact(
        'totalEmployees',
        'totalAttendance',
        'pendingLeaveRequests',
        'pendingOvertimeRequests',
        'daysUntilNextPayroll',
        'attendanceTrend'
    ));

}
}
