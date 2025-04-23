<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class shifts extends Model

{

    protected $fillable = [
        'StartTime',
        'EndTime',
    ];

    public function employeeshifts()

    {
        return $this->hasMany(employeeshifts::class, 'ShiftID');
    }
    public function employees()
    {
        return $this->belongsToMany(Shifts::class, 'employeeshifts', 'ShiftID', 'EmployeeID');
    }
}
