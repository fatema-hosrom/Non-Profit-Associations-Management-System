<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Supervisor extends Authenticatable
{
    use Notifiable;

    protected $table = 'supervisor';

    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'phone',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
}
