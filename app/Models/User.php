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
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCSLayer1()
    {
        return $this->role === 'cs_layer1';
    }

    public function isCSLayer2()
    {
        return $this->role === 'cs_layer2';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }
}