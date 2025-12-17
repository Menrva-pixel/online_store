<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
            
        $allProducts = Product::where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('home.index', compact('featuredProducts', 'allProducts'));
    }

    public function showProduct(Product $product)
    {
        // Check if product is in user's cart
        $inCart = false;
        $cartQuantity = 0;
        
        if (auth()->check()) {
            $cartItem = Cart::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->first();
                
            if ($cartItem) {
                $inCart = true;
                $cartQuantity = $cartItem->quantity;
            }
        }
        
        return view('home.product-detail', compact('product', 'inCart', 'cartQuantity'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $products = Product::where('stock', '>', 0)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();
            
        return view('home.search', compact('products', 'query'));
    }
}