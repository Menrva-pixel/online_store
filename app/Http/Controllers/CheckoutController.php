<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;  
use App\Models\Cart;
use App\Models\Product;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:bank_transfer,cod,ewallet',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        
        DB::beginTransaction();
        
        try {
            // Ambil item cart
            $cartItems = Cart::where('user_id', $user->id)
                ->with('product')
                ->get();
            
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Keranjang belanja kosong.');
            }
            
            // Hitung total dengan benar sesuai dengan di showCheckout()
            $subtotal = 0;
            foreach ($cartItems as $item) {
                // Cek stok
                if ($item->product->stock < $item->quantity) {
                    return redirect()->route('cart.index')
                        ->with('error', "Stok {$item->product->name} tidak mencukupi.");
                }
                $subtotal += $item->product->price * $item->quantity;
            }
            
            // Hitung pajak dan pengiriman
            $tax = $subtotal * 0.1;
            $shipping = 15000;
            $total = $subtotal + $tax + $shipping;
            
            // Tambah biaya COD jika dipilih
            if ($request->payment_method == 'cod') {
                $total += 5000; 
            }
            
            // PERBAIKAN: Sesuaikan dengan field di model Order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . time() . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes ?? '',
                'total' => $total, // Hanya total, subtotal/tax/shipping disimpan di field lain jika ada
                'shipping_cost' => $shipping,
                'status' => ($request->payment_method == 'cod') ? 'pending' : 'waiting_payment',
            ]);
            
            // Buat order items dengan SEMUA field yang required
            foreach ($cartItems as $item) {
                $itemPrice = $item->product->price;
                $itemQuantity = $item->quantity;
                $itemSubtotal = $itemPrice * $itemQuantity;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $itemQuantity,
                    'price' => $itemPrice,
                    'subtotal' => $itemSubtotal, 
                ]);
                
                // Kurangi stok
                $item->product->decrement('stock', $itemQuantity);
            }
            
            // Hapus cart
            Cart::where('user_id', $user->id)->delete();
            
            // Buat payment proof dummy HANYA jika bukan COD
            if ($request->payment_method != 'cod') {
                PaymentProof::create([
                    'order_id' => $order->id,
                    'proof_image' => 'dummy/payment-proof.jpg',
                    'payment_method' => $request->payment_method,
                    'bank_name' => $request->payment_method == 'bank_transfer' ? 'Bank Dummy' : null,
                    'account_number' => $request->payment_method == 'bank_transfer' ? '1234567890' : null,
                    'notes' => 'Dummy payment proof for testing',
                    'status' => 'pending',
                    'verified_at' => null,
                    'verified_by' => null,
                ]);
            }
            
            DB::commit();
            
            // Redirect berdasarkan payment method
            if ($request->payment_method != 'cod') {
                return redirect()->route('my.orders.show', $order)
                    ->with('success', 'Pesanan berhasil dibuat! Silakan upload bukti pembayaran.');
            }
            
            // Jika COD, langsung ke detail order
            return redirect()->route('my.orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat! No Order: ' . $order->order_number);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Checkout error: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
            
            return redirect()->route('checkout')
                ->with('error', 'Terjadi kesalahan saat memproses pesanan. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show payment upload page for customer
     */
    public function showPayment(Order $order)
    {
        // cek jika pesanan milik user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Cek jika order sudah memiliki payment proof
        if ($order->paymentProof) {
            return redirect()->route('my.orders.show', $order)
                ->with('info', 'Bukti pembayaran sudah diupload.');
        }

        // Cek jika payment method adalah COD
        if ($order->payment_method == 'cod') {
            return redirect()->route('my.orders.show', $order)
                ->with('info', 'Pesanan COD tidak memerlukan upload bukti pembayaran.');
        }

        // Cek status order
        if ($order->status != 'waiting_payment') {
            return redirect()->route('my.orders.show', $order)
                ->with('error', 'Pesanan tidak dalam status menunggu pembayaran.');
        }

        return view('orders.payment-upload', compact('order'));
    }

    /**
     * Handle payment proof upload
     */
    public function uploadPayment(Request $request, Order $order)
    {
        // cek jika pesanan milik user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Validasi
        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // Upload image
            $path = $request->file('proof_image')->store('payment-proofs', 'public');
            
            // Update atau create payment proof
            if ($order->paymentProof) {
                // Hapus file lama jika ada
                if ($order->paymentProof->proof_image && Storage::disk('public')->exists($order->paymentProof->proof_image)) {
                    Storage::disk('public')->delete($order->paymentProof->proof_image);
                }
                
                $order->paymentProof->update([
                    'proof_image' => $path,
                    'notes' => $request->notes,
                    'status' => 'pending',
                    'verified_at' => null,
                    'verified_by' => null,
                ]);
            } else {
                PaymentProof::create([
                    'order_id' => $order->id,
                    'proof_image' => $path,
                    'payment_method' => $order->payment_method,
                    'notes' => $request->notes,
                    'status' => 'pending',
                    'verified_at' => null,
                    'verified_by' => null,
                ]);
            }
            
            return redirect()->route('my.orders.show', $order)
                ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi dari CS Layer 1.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupload bukti pembayaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show order details for customer (my orders)
     */
    public function showMyOrder(Order $order)
    {
        // Cek jika pesanan milik user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['items.product', 'paymentProof']);
        
        return view('orders.customer-show', compact('order'));
    }

    /**
     * Show order details (for admin/cs - legacy method)
     * @deprecated - Gunakan controller terpisah untuk admin/cs
     */
    public function showOrder(Order $order)
    {
        // cek jika pesanan milik user atau diakses oleh CS Layer
        if ($order->user_id !== auth()->id() && 
            !auth()->user()->isAdmin() && 
            !auth()->user()->isCSLayer1() && 
            !auth()->user()->isCSLayer2()) {
            abort(403, 'Unauthorized access.');
        }

        $order->load(['items.product', 'paymentProof']);

        // Redirect ke route yang sesuai berdasarkan role
        if ($order->user_id === auth()->id()) {
            return redirect()->route('my.orders.show', $order);
        } elseif (auth()->user()->isAdmin()) {
            return redirect()->route('admin.orders.show', $order);
        } elseif (auth()->user()->isCSLayer2()) {
            return redirect()->route('cs2.orders.show', $order);
        }

        return view('orders.show', compact('order'));
    }

    public function cancelOrder(Order $order)
    {
        // cek jika pesanan milik user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // cek jika pesanan dapat dibatalkan
        if (!in_array($order->status, ['waiting_payment', 'pending'])) {
            return redirect()->route('my.orders.show', $order)
                ->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
            // Kembalikan stok produk
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
            
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
            ]);

            DB::commit();
            
            return redirect()->route('my.orders.show', $order)
                ->with('success', 'Pesanan berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('my.orders.show', $order)
                ->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }

    public function listOrders()
    {
        $orders = auth()->user()->orders()
            ->with(['items.product', 'paymentProof'])
            ->latest()
            ->paginate(10);

        return view('orders.customer-index', compact('orders'));
    }
}