<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class empuser extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'empusers'; // Specify the correct table name

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 'employee_id',
        'name',
        'email',
        'role',
        'password',
    ];

    /**
     * Create a new empuser.
     *
     * @param array $data
     * @return empuser
     */
    public static function createEmpuser(array $data)
    {
        return self::create([
            // 'employee_id' => $data['employee_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => bcrypt($data['password']), // Hash the password
        ]);
    }
}
