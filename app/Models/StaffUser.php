<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class StaffUser extends Model
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        'username',
        'email',
        'password',
        'position',
        'division',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
}
