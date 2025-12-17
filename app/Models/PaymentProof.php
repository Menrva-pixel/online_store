<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'proof_image',
        'payment_method',
        'bank_name',
        'account_number',
        'notes',
        'status',
        'verified_by'
    ];

    protected $casts = [
        'verified_at' => 'datetime'
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => ['text' => 'Menunggu Verifikasi', 'color' => 'bg-yellow-100 text-yellow-800'],
            'verified' => ['text' => 'Terverifikasi', 'color' => 'bg-green-100 text-green-800'],
            'rejected' => ['text' => 'Ditolak', 'color' => 'bg-red-100 text-red-800']
        ];

        return $labels[$this->status] ?? ['text' => 'Unknown', 'color' => 'bg-gray-100 text-gray-800'];
    }

    public function getProofImageUrlAttribute()
    {
        return asset('storage/' . $this->proof_image);
    }
}