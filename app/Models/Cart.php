<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Total Harga Item
    public function getTotalPriceAttribute()
    {
        return $this->product->price * $this->quantity;
    }

    // Total Harga Terformat
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    // Cek jika item tersedia berdasarkan stok
    public function getIsAvailableAttribute()
    {
        return $this->product->stock >= $this->quantity;
    }

    // pesan ketersediaan
    public function getAvailabilityMessageAttribute()
    {
        if ($this->product->stock <= 0) {
            return 'Habis';
        } elseif ($this->product->stock < $this->quantity) {
            return "Stok hanya {$this->product->stock} tersedia";
        } else {
            return 'Tersedia';
        }
    }
}