<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    use HasFactory;
    protected $table = 'attendances'; // Explicitly define the table name
    protected $fillable = ['EmployeeID', 'Type', 'DateTime'];
    protected $casts = [
        'DateTime' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'EmployeeID');
    }
}
