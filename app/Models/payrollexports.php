<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class payrollexports extends Model
{
    protected $fillable = [
        'EmployeeID',
        'PayPeriodStart',
        'PayPeriodEnd',
        'DaysWorked',
        'OvertimeHours',
        'LeaveDays',
        'AbsentDays',
        'LateMinutes',
        'GrossPay',
        'NetPay',
        'ExportDate'
    ];

    protected $casts = [
        'PayPeriodStart' => 'date',
        'PayPeriodEnd' => 'date',
        'ExportDate' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'EmployeeID');
    }
}
