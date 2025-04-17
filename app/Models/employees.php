<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employees extends Model
{
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
        'QRCode',
        'BaseSalary',
        'Email',
    ];


    public function position()
    {
        return $this->belongsTo(EmpPosition::class, 'PositionID');
    }

    /**
     * Define the relationship with the Department model.
     */
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
        return $this->hasMany(attendances::class, 'EmployeeID');
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
    public function employeeShift()
    {
        return $this->hasOne(employeeshifts::class, 'EmployeeID');
    }
}