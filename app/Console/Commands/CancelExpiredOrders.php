<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired';
    protected $description = 'Cancel orders that have not been paid within 24 hours';

    public function handle()
    {
        $expiredOrders = Order::where('status', 'waiting_payment')
            ->where('payment_expiry_at', '<=', now())
            ->whereNotNull('payment_expiry_at')
            ->with('items.product')
            ->get();

        foreach ($expiredOrders as $order) {
            DB::beginTransaction();
            try {
                // Kembalikan stok
                foreach ($order->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }
                
                // Update status order
                $order->update([
                    'status' => 'cancelled',
                    'auto_cancelled_at' => now(),
                    'cancellation_reason' => 'Pesanan otomatis dibatalkan karena tidak melakukan pembayaran dalam 24 jam'
                ]);
                
                $this->info("Order {$order->order_number} cancelled automatically.");
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Failed to cancel order {$order->order_number}: {$e->getMessage()}");
            }
        }
        
        $this->info("{$expiredOrders->count()} orders cancelled.");
    }
}