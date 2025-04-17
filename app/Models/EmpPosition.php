<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpPosition extends Model
{
    protected $table = 'emppositions'; // Explicitly define the table name
    protected $fillable = [
        'PositionName',
    ];

    public function employees()
    {
        return $this->hasMany(employees::class, 'PositionID');
    }
}
