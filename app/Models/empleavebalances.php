<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class empleavebalances extends Model
{

    protected $fillable = ['EmployeeID', 'VacationLeave', 'SickLeave'];
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'EmployeeID');
    }
}
