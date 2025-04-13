<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class emppositions extends Model
{
    protected $fillable = [
        'PositionName',
    ];

    public function employees()
    {
        return $this->hasMany(employees::class, 'PositionID');
    }
}