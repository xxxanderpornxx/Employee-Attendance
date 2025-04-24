<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model

    // Define the table associated with the model
{   use HasFactory;
     protected $table = 'employees';
    protected $fillable = [
    'FirstName',
    'MiddleName',
    'LastName',
    'Sex',
    'DateOfBirth',
    'PositionID',
    'DepartmentID',
    'ContactNumber',
    'Address',
    'HireDate',
    'QRcode',
    'BaseSalary',
    'Email',
    ];


    public function position()
    {
        return $this->belongsTo(EmpPosition::class, 'PositionID');
    }


    public function department()
    {
        return $this->belongsTo(Department::class, 'DepartmentID');
    }

    /**
     * Define the relationship with the LeaveRequest model.
     */
    public function leaveRequests()
    {
        return $this->hasMany(leaveRequests::class, 'EmployeeID');
    }

    /**
     * Define the relationship with the OvertimeRequest model.
     */
    public function overtimeRequests()
    {
        return $this->hasMany(overtimeRequests::class, 'EmployeeID');
    }

    /**
     * Define the relationship with the EmpLeaveBalance model.
     */
    public function empLeaveBalance()
    {
        return $this->hasOne(empleavebalances::class, 'EmployeeID');
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
    public function Employeeshift()
    {
        return $this->hasOne(Employeeshifts::class, 'EmployeeID');
    }
    public function shifts()
    {
        return $this->belongsToMany(Shifts::class, 'employeeshifts', 'EmployeeID', 'ShiftID');
    }
}