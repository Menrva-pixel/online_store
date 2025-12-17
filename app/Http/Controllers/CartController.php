<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->carts()->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->total;
        });
        
        return view('cart.index', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->back()
            ->with('success', 'Product added to cart.');
    }

    public function updateCart(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cart->product->stock
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')
            ->with('success', 'Cart updated.');
    }

    public function removeFromCart(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('cart.index')
            ->with('success', 'Item removed from cart.');
    }
}