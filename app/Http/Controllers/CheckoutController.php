<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function showCheckout()
    {
        $cartItems = auth()->user()->carts()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja kosong. Tambahkan produk terlebih dahulu.');
        }

        // cek stok ketersediaan untuk setiap item
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')
                    ->with('error', 
                        "Stok {$item->product->name} tidak mencukupi. Hanya tersedia {$item->product->stock} item."
                    );
            }
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $tax = $subtotal * 0.1;
        $shipping = 15000;
        $total = $subtotal + $tax + $shipping;

        return view('checkout.index', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|min:10',
            'notes' => 'nullable|string|max:500'
        ]);

        $cartItems = auth()->user()->carts()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja kosong.');
        }

        DB::beginTransaction();

        try {
            // Hitung total
            $subtotal = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
            
            $tax = $subtotal * 0.1;
            $shipping = 15000;
            $total = $subtotal + $tax + $shipping;

            //  buat pesanan
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => $this->generateOrderNumber(),
                'total_amount' => $total,
                'tax_amount' => $tax,
                'shipping_cost' => $shipping,
                'status' => 'waiting_payment',
                'shipping_address' => $request->shipping_address,
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'notes' => $request->notes,
                'payment_due_at' => now()->addHours(24)
            ]);

            // buat item pesanan
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $cartItem->quantity,
                    'price' => $product->price,
                    'subtotal' => $product->price * $cartItem->quantity
                ]);

                // Kurangi stok produk (tapi jangan commit dulu - akan dikonfirmasi setelah verifikasi pembayaran)
                // Stok akan dikurangi hanya setelah verifikasi pembayaran oleh CS Layer 1
                // $product->decrement('stock', $cartItem->quantity);
            }

            // bersihkan keranjang
            auth()->user()->carts()->delete();

            DB::commit();

            return redirect()->route('payment.show', $order)
                ->with('success', 'Pesanan berhasil dibuat! Silakan upload bukti pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showPayment(Order $order)
    {
        // cek jika pesanan milik user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // cek jika pesanan dalam status menunggu pembayaran
        if ($order->status !== 'waiting_payment') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Status pesanan tidak valid untuk pembayaran.');
        }

        return view('payment.upload', compact('order'));
    }

    public function uploadPayment(Request $request, Order $order)
    {
        // cek jika pesanan milik user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // cek jika pesanan dalam status menunggu pembayaran
        if ($order->status !== 'waiting_payment') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Status pesanan tidak valid untuk pembayaran.');
        }

        $request->validate([
            'payment_method' => 'required|in:bank_transfer,e_wallet,cod',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'bank_name' => 'required_if:payment_method,bank_transfer|string|max:100',
            'account_number' => 'required_if:payment_method,bank_transfer|string|max:50',
            'notes' => 'nullable|string|max:500'
        ]);

        //  simpan gambar bukti pembayaran
        $imagePath = $request->file('proof_image')->store('payment-proofs', 'public');

        // buat bukti pembayaran
        PaymentProof::create([
            'order_id' => $order->id,
            'proof_image' => $imagePath,
            'payment_method' => $request->payment_method,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        //  perbarui status pesanan (opsional)
        $order->update(['status' => 'waiting_payment']);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi CS Layer 1.');
    }

    public function showOrder(Order $order)
    {
        // cek jika pesanan milik user atau diakses oleh CS Layer
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin() && 
            !auth()->user()->isCSLayer1() && !auth()->user()->isCSLayer2()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['items.product', 'paymentProof']);

        return view('orders.show', compact('order'));
    }

    public function cancelOrder(Order $order)
    {
        // cek jika pesanan milik user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // cek jika pesanan dapat dibatalkan
        if (!$order->canBeCancelled()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        // Kembalikan stok produk

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function listOrders()
    {
        $orders = auth()->user()->orders()
            ->with(['items.product', 'paymentProof'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    private function generateOrderNumber()
    {
        return 'ORD' . date('Ymd') . strtoupper(Str::random(6));
    }
}