<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CSLayer2Controller extends Controller
{
    public function dashboard()
    {
        $orders = Order::where('status', 'paid')
            ->orWhere('status', 'processing')
            ->orWhere('status', 'shipped')
            ->with(['user', 'items.product'])
            ->latest()
            ->paginate(10);
            
        return view('cs.layer2.dashboard', compact('orders'));
    }

    public function processOrder(Order $order)
    {
        $order->update([
            'status' => 'processing',
            'processed_at' => now()
        ]);

        // Generasi packing slip
        // Notifikasi ke warehouse/packing team

        return redirect()->route('cs2.dashboard')
            ->with('success', 'Order marked as processing.');
    }

    public function shipOrder(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
            'shipping_carrier' => 'required|string|max:50'
        ]);

        $order->update([
            'status' => 'shipped',
            'tracking_number' => $request->tracking_number,
            'shipping_carrier' => $request->shipping_carrier,
            'shipped_at' => now()
        ]);

        // Notifikasi ke customer tentang pengiriman

        return redirect()->route('cs2.dashboard')
            ->with('success', 'Order marked as shipped.');
    }

    public function completeOrder(Order $order)
    {
        $order->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return redirect()->route('cs2.dashboard')
            ->with('success', 'Order marked as completed.');
    }
}