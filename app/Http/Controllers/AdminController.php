<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            // Ambil data produk stok rendah
            $lowStockProducts = Product::where('stock', '<', 10)
                ->orderBy('stock', 'asc')
                ->take(10)
                ->get();

            // Ambil data pesanan terbaru
            $recentOrders = Order::with(['user', 'items.product'])
                ->latest()
                ->take(10)
                ->get();

            // Buat array stats yang lengkap termasuk data untuk view
            $stats = [
                'total_products' => Product::count(),
                'low_stock' => Product::where('stock', '<', 10)->count(),
                'out_of_stock' => Product::where('stock', 0)->count(),
                'total_orders' => Order::count(),
                'pending_orders' => Order::whereIn('status', ['pending', 'waiting_payment'])->count(),
                'total_users' => User::count(),
                // ✅ PERBAIKAN: Tambahkan data yang dibutuhkan view
                'low_stock_products' => $lowStockProducts,
                'recent_orders' => $recentOrders,
            ];

            $ordersByStatus = [
                'pending' => Order::where('status', 'pending')->count(),
                'waiting_payment' => Order::where('status', 'waiting_payment')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'shipped' => Order::where('status', 'shipped')->count(),
                'completed' => Order::where('status', 'completed')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
            ];

            return view('admin.dashboard', compact(
                'stats', 
                'ordersByStatus'
                // ✅ PERBAIKAN: Tidak perlu kirim $recentOrders dan $lowStockProducts secara terpisah
                // karena sudah ada di dalam $stats
            ));

        } catch (\Exception $e) {
            \Log::error('Admin dashboard error: ' . $e->getMessage());
            
            // ✅ PERBAIKAN: Berikan struktur stats yang lengkap saat error
            return view('admin.dashboard', [
                'stats' => [
                    'total_products' => 0,
                    'low_stock' => 0,
                    'out_of_stock' => 0,
                    'total_orders' => 0,
                    'pending_orders' => 0,
                    'total_users' => 0,
                    'low_stock_products' => collect(),
                    'recent_orders' => collect(),
                ],
                'ordersByStatus' => [],
            ]);
        }
    }

    public function products(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('stock')) {
            if ($request->stock === 'low') {
                $query->where('stock', '<', 10);
            } elseif ($request->stock === 'out') {
                $query->where('stock', 0);
            }
        }

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $products = $query->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('admin.products.create');
    }

    public function storeProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $productData = $request->only(['name', 'description', 'price', 'stock']);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $productData['image'] = $imagePath;
        }

        Product::create($productData);

        return redirect()->route('admin.products')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function editProduct(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $productData = $request->only(['name', 'description', 'price', 'stock']);
        
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
            $productData['image'] = $imagePath;
        }

        $product->update($productData);

        return redirect()->route('admin.products')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function deleteProduct(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function showImportForm()
    {
        return view('admin.products.import');
    }

    public function importProducts(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->route('admin.products')
                ->with('success', 'Produk berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    public function orders(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'paymentProof']);

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

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['user', 'items.product', 'paymentProof']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,waiting_payment,processing,shipped,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:customer,admin,cs_layer1,cs_layer2'
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->back()
            ->with('success', 'Role pengguna berhasil diperbarui.');
    }
}