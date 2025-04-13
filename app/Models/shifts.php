<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class shifts extends Model

{

    protected $fillable = [
        'StartTime',
        'EndTime',
    ];

    public function employeeShifts()

    {
        return $this->hasMany(employeeShifts::class, 'ShiftID');
    }
}
