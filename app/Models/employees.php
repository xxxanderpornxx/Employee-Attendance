<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'FirstName', 'MiddleName', 'LastName', 'Sex', 'DateOfBirth',
        'PositionID', 'DepartmentID', 'ContactNumber', 'Address',
        'HireDate', 'QRcode', 'BaseSalary', 'Email'
    ];

    public function position()
    {
        return $this->belongsTo(EmpPosition::class, 'PositionID');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'DepartmentID');
    }


    public function leaveRequests()
    {
        return $this->hasMany(leaveRequests::class, 'EmployeeID');
    }


    public function overtimeRequests()
    {
        return $this->hasMany(overtimeRequests::class, 'EmployeeID');
    }


    public function leaveBalance()
    {
        return $this->hasOne(Empleavebalances::class, 'EmployeeID');
    }

    /**
     * Define the relationship with the Attendance model.
     */
    public function attendances()
    {
        return $this->hasMany(Attendances::class, 'EmployeeID');
    }

    /**
     * Define the relationship with the PayrollExport model.
     */
    public function payrollExports()
    {
        return $this->hasMany(payrollExports::class, 'EmployeeID');
    }

    /**
     * Define the relationship with the EmployeeShift model.
     */


    public function shifts()
    {
     return $this->belongsToMany(shifts::class, 'employee_schedules', 'EmployeeID', 'ShiftID')
                ->withPivot('DayOfWeek')
                ->withTimestamps();
    }
    public function schedules()
    {
        return $this->hasMany(EmployeeSchedules::class, 'EmployeeID');
    }
    public function employee()
    {
        return $this->hasOne(Employees::class, 'Email', 'email'); // Match email fields
    }

    protected $hidden = [
        'Password', 'remember_token',
    ];
}