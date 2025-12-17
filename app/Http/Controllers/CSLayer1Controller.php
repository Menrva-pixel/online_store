<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CSLayer1Controller extends Controller
{
    public function dashboard()
    {
        $pendingPayments = PaymentProof::where('status', 'pending')
            ->with(['order.user'])
            ->latest()
            ->paginate(10);
            
        return view('cs.layer1.dashboard', compact('pendingPayments'));
    }

    public function verifyPayment(PaymentProof $paymentProof)
    {
        DB::transaction(function () use ($paymentProof) {
            // Update status pembayaran
            $paymentProof->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id()
            ]);

            // Update status order menjadi 'paid'
            $paymentProof->order->update([
                'status' => 'paid'
            ]);

            // Mengurangi stok produk sesuai jumlah dalam order
            foreach ($paymentProof->order->items as $item) {
                $product = $item->product;
                $product->decrement('stock', $item->quantity);
            }

            // Mengirim email konfirmasi ke customer
        });

        return redirect()->route('cs1.dashboard')
            ->with('success', 'Payment verified successfully.');
    }

    public function rejectPayment(Request $request, PaymentProof $paymentProof)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $paymentProof->update([
            'status' => 'rejected',
            'notes' => $request->rejection_reason
        ]);

        // Mengirim notifikasi penolakan ke customer

        return redirect()->route('cs1.dashboard')
            ->with('success', 'Payment rejected.');
    }

    public function pendingOrders()
    {
        $orders = Order::where('status', 'paid')
            ->with(['user', 'paymentProof'])
            ->latest()
            ->paginate(10);
            
        return view('cs.layer1.orders', compact('orders'));
    }
}