<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'tax_amount',
        'shipping_cost',
        'status',
        'shipping_address',
        'recipient_name',
        'recipient_phone',
        'notes',
        'payment_due_at'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'payment_due_at' => 'datetime',
        'cancelled_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentProof()
    {
        return $this->hasOne(PaymentProof::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeWaitingPayment($query)
    {
        return $query->where('status', 'waiting_payment');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Accessors
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedTaxAttribute()
    {
        return 'Rp ' . number_format($this->tax_amount, 0, ',', '.');
    }

    public function getFormattedShippingAttribute()
    {
        return 'Rp ' . number_format($this->shipping_cost, 0, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => ['text' => 'Menunggu', 'color' => 'bg-yellow-100 text-yellow-800'],
            'waiting_payment' => ['text' => 'Menunggu Pembayaran', 'color' => 'bg-blue-100 text-blue-800'],
            'processing' => ['text' => 'Diproses', 'color' => 'bg-purple-100 text-purple-800'],
            'shipped' => ['text' => 'Dikirim', 'color' => 'bg-indigo-100 text-indigo-800'],
            'completed' => ['text' => 'Selesai', 'color' => 'bg-green-100 text-green-800'],
            'cancelled' => ['text' => 'Dibatalkan', 'color' => 'bg-red-100 text-red-800']
        ];

        return $labels[$this->status] ?? ['text' => 'Unknown', 'color' => 'bg-gray-100 text-gray-800'];
    }

    // Methods
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'waiting_payment']);
    }

    public function hasPaymentProof()
    {
        return $this->paymentProof()->exists();
    }

    public function generateOrderNumber()
    {
        return 'ORD' . date('Ymd') . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}