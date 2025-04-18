<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employeeshifts extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'EmployeeID');
    }

    public function shift()
    {
        return $this->belongsTo(shifts::class, 'ShiftID');
    }
}
