<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class leaverequests extends Model
{
    protected $table = 'leaverequests'; // Specify the correct table name

    protected $fillable = [
        'EmployeeID',
        'StartDate',
        'EndDate',
        'LeaveType',
        'Reason',
        'Status',
    ];
    public function employee()
    {
        return $this->belongsTo(employees::class, 'EmployeeID');
    }
}