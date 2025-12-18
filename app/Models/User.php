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

    // Relationships

      /**
     * Get all orders for the user
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // Role Checks
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

    // Cart related methods
    public function getCartTotalAttribute()
    {
        return $this->carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });
    }

    public function getCartItemsCountAttribute()
    {
        return $this->carts->count();
    }
}