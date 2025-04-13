<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class departments extends Model
{
    public function employees()
{
    return $this->hasMany(employees::class, 'DepartmentID');
}

}