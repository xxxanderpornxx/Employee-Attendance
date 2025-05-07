<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Extend the base User class
use Illuminate\Notifications\Notifiable;

class empuser extends Authenticatable
{
    use Notifiable;

    protected $table = 'empusers'; // Specify the correct table name


    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];



    protected $hidden = [
        'password', 'remember_token',
    ];
}
