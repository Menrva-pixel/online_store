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
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'payment_method',
        'notes',
        'total',
        'subtotal',
        'tax',
        'status',
        'shipping_courier',
        'tracking_number',
        'shipping_cost',
        'processed_by',
        'processed_at',
        'shipped_by',
        'shipped_at',
        'completed_by',
        'completed_at',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
        'payment_expiry_at', 
        'auto_cancelled_at'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'processed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'payment_expiry_at' => 'datetime',
        'auto_cancelled_at' => 'datetime',

    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the payment proof for the order.
     */
    public function paymentProof()
    {
        return $this->hasOne(PaymentProof::class);
    }

    /**
     * Get the user who processed the order.
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the user who shipped the order.
     */
    public function shipper()
    {
        return $this->belongsTo(User::class, 'shipped_by');
    }

    /**
     * Get the user who completed the order.
     */
    public function completer()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Get the user who cancelled the order.
     */
    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => ['text' => 'Pending', 'color' => 'bg-yellow-100 text-yellow-800'],
            'waiting_payment' => ['text' => 'Menunggu Bayar', 'color' => 'bg-blue-100 text-blue-800'],
            'processing' => ['text' => 'Diproses', 'color' => 'bg-purple-100 text-purple-800'],
            'shipped' => ['text' => 'Dikirim', 'color' => 'bg-indigo-100 text-indigo-800'],
            'completed' => ['text' => 'Selesai', 'color' => 'bg-green-100 text-green-800'],
            'cancelled' => ['text' => 'Dibatalkan', 'color' => 'bg-red-100 text-red-800']
        ];
        
        return $statuses[$this->status] ?? ['text' => 'Unknown', 'color' => 'bg-gray-100 text-gray-800'];
    }

    /**
     * Get formatted total.
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    /**
     * Get total with shipping.
     */
    public function getTotalWithShippingAttribute()
    {
        return $this->total + ($this->shipping_cost ?? 0);
    }

    /**
     * Get formatted total with shipping.
     */
    public function getFormattedTotalWithShippingAttribute()
    {
        return 'Rp ' . number_format($this->total_with_shipping, 0, ',', '.');
    }

    public function isPaymentExpired()
    {
        return $this->status === 'waiting_payment' 
            && $this->payment_expiry_at 
            && now()->greaterThan($this->payment_expiry_at);
    }
    public function getPaymentExpiryFormattedAttribute()
    {
        return $this->payment_expiry_at 
            ? Carbon::parse($this->payment_expiry_at)->format('d F Y H:i')
            : 'Tidak ada batas waktu';
    }

    public function getRemainingPaymentTimeAttribute()
    {
        if (!$this->payment_expiry_at || $this->status !== 'waiting_payment') {
            return null;
        }
        
        $now = Carbon::now();
        $expiry = Carbon::parse($this->payment_expiry_at);
        
        if ($now->greaterThan($expiry)) {
            return 'Waktu habis';
        }
        
        return $now->diff($expiry)->format('%h jam %i menit %s detik');
    }
}