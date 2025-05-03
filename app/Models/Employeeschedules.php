<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employeeschedules extends Model
{   protected $table = 'employee_schedules';
    protected $fillable = [
        'EmployeeID',
        'ShiftID',
        'DayOfWeek',
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'EmployeeID');
    }

    public function shift()
    {
        return $this->belongsTo(shifts::class, 'ShiftID');
    }
}
