<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    use HasFactory;

    protected $fillable = ['EmployeeID', 'Type', 'DateTime'];

    public function employees()
    {
        return $this->belongsTo(Employees::class, 'EmployeeID');
    }
}
