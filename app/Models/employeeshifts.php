<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employeeshifts extends Model
{
    protected $fillable = [
        'EmployeeID',
        'ShiftID',
    ];
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'EmployeeID');
    }

    public function shift()
    {
        return $this->belongsTo(Shifts::class, 'ShiftID');
    }
}
