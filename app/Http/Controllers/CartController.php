<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->carts()->with('product')->get();
        
        // Check stock availability for each item
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                session()->flash('warning', 
                    "Stok {$item->product->name} hanya tersedia {$item->product->stock} item. Silakan perbarui jumlah pesanan."
                );
            }
        }
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $tax = $subtotal * 0.1; // 10% tax
        $shipping = 15000; 
        $total = $subtotal + $tax + $shipping;
        
        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        // Check if product already in cart
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Check if new quantity exceeds stock
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $product->stock) {
                return redirect()->back()
                    ->with('error', 
                        "Tidak dapat menambah jumlah. Stok {$product->name} hanya tersedia {$product->stock} item."
                    );
            }
            
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', "{$product->name} berhasil ditambahkan ke keranjang.");
    }

    public function updateCart(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cart->product->stock
        ]);

        // Check if cart belongs to user
        if ($cart->user_id !== auth()->id()) {
            return redirect()->route('cart.index')
                ->with('error', 'Akses ditolak.');
        }

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')
            ->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function removeFromCart(Cart $cart)
    {
        // Check if cart belongs to user
        if ($cart->user_id !== auth()->id()) {
            return redirect()->route('cart.index')
                ->with('error', 'Akses ditolak.');
        }

        $productName = $cart->product->name;
        $cart->delete();

        return redirect()->route('cart.index')
            ->with('success', "{$productName} berhasil dihapus dari keranjang.");
    }

    public function clearCart()
    {
        auth()->user()->carts()->delete();
        
        return redirect()->route('cart.index')
            ->with('success', 'Semua item berhasil dihapus dari keranjang.');
    }

    public function getCartCount()
    {
        $count = auth()->user()->carts()->count();
        return response()->json(['count' => $count]);
    }

    public function calculateTotals()
    {
        $cartItems = auth()->user()->carts()->with('product')->get();
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $tax = $subtotal * 0.1;
        $shipping = 15000;
        $total = $subtotal + $tax + $shipping;
        
        return response()->json([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'items_count' => $cartItems->count()
        ]);
    }
}