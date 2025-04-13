<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class leaverequests extends Model
{
    public function employee()
    {
        return $this->belongsTo(employees::class, 'EmployeeID');
    }
}
