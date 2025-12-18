<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CSLayer2Controller extends Controller
{
    public function dashboard()
    {
        // PERBAIKAN: Tambah status 'waiting_payment' untuk menunggu pembayaran
        $processingOrders = Order::where('status', 'processing')
            ->with(['user', 'items.product'])
            ->latest()
            ->take(10)
            ->get();

        $shippedOrders = Order::where('status', 'shipped')
            ->with(['user', 'items.product'])
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'waiting_payment' => Order::where('status', 'waiting_payment')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
        ];

        return view('cs2.dashboard', compact('processingOrders', 'shippedOrders', 'stats'));
    }

    public function orders(Request $request)
    {
        // PERBAIKAN: Tambah status 'waiting_payment' untuk CS2 bisa lihat
        $query = Order::whereIn('status', ['waiting_payment', 'processing', 'shipped'])
            ->with(['user', 'items.product']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(20);

        return view('cs2.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['user', 'items.product', 'paymentProof']);
        return view('cs2.orders.show', compact('order'));
    }

    public function processOrder(Order $order)
    {
        // status hanya bisa dari waiting_payment ke processing
        if ($order->status !== 'waiting_payment') {
            return redirect()->back()
                ->with('error', 'Hanya pesanan dengan status "Menunggu Pembayaran" yang dapat diproses.');
        }

        $order->update([
            'status' => 'processing',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Pesanan berhasil diproses.');
    }

    public function shipOrder(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
            'shipping_courier' => 'required|string|max:50',
        ]);

        // Hanya dari processing ke shipped
        if ($order->status !== 'processing') {
            return redirect()->back()
                ->with('error', 'Hanya pesanan dengan status "Diproses" yang dapat dikirim.');
        }

        $order->update([
            'status' => 'shipped',
            'tracking_number' => $request->tracking_number,
            'shipping_courier' => $request->shipping_courier,
            'shipped_by' => auth()->id(),
            'shipped_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Pesanan berhasil dikirim.');
    }

    public function completeOrder(Order $order)
    {
        if ($order->status !== 'shipped') {
            return redirect()->back()
                ->with('error', 'Hanya pesanan yang sudah dikirim yang dapat diselesaikan.');
        }

        $order->update([
            'status' => 'completed',
            'completed_by' => auth()->id(),
            'completed_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Pesanan berhasil diselesaikan.');
    }

    public function cancelOrder(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        // Batalkan dari status apa saja kecuali completed/cancelled
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return redirect()->back()
                ->with('error', 'Pesanan yang sudah selesai atau dibatalkan tidak dapat dibatalkan lagi.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_by' => auth()->id(),
            'cancelled_at' => now(),
            'cancellation_reason' => $request->reason,
        ]);

        return redirect()->back()
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function printPackingSlip(Order $order)
    {
        $order->load(['user', 'items.product']);
        
        $pdf = Pdf::loadView('cs2.orders.packing-slip', compact('order'));
        
        return $pdf->stream('packing-slip-' . $order->order_number . '.pdf');
    }

    public function downloadShippingLabels(Order $order)
    {
        $order->load(['user', 'items.product']);
        
        $pdf = Pdf::loadView('cs2.orders.shipping-label', compact('order'));
        
        return $pdf->download('shipping-label-' . $order->order_number . '.pdf');
    }
}