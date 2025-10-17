<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'country',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed', // facultatif selon ta version/usage
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
