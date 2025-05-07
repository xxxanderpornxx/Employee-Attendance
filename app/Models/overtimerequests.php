<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class overtimerequests extends Model
{
    protected $table = 'overtimerequests'; // Specify the correct table name

    protected $fillable = [
        'EmployeeID',
        'Date',
        'StartTime',
        'EndTime',
        'Status',
    ];
    public function employee()
    {
        return $this->belongsTo(employees::class, 'EmployeeID');
    }
}
