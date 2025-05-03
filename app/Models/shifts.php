<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class shifts extends Model

{

    protected $fillable = [
        'ShiftName',
        'StartTime',
        'EndTime',
    ];

    public function employeeshifts()
    {
        return $this->hasMany(Employeeshifts::class, 'ShiftID');
    }
    public function employees()
    {
        return $this->belongsToMany(Employees::class, 'employeeshifts', 'ShiftID', 'EmployeeID');
    }
    public function schedules()
    {
        return $this->hasMany(EmployeeSchedules::class);
    }
}