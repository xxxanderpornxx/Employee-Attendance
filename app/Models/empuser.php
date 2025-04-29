<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Extend the base User class
use Illuminate\Notifications\Notifiable;

class empuser extends Authenticatable
{
    use Notifiable;

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
        'name',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];
}
