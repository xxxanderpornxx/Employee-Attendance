<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments'; // Explicitly define the table name
    protected $fillable = [
        'DepartmentName',
    ];
    public function employee()
{
    return $this->hasMany(Employees::class, 'DepartmentID');
}

}
