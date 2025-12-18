<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
            ));

        } catch (\Exception $e) {
            \Log::error('Admin dashboard error: ' . $e->getMessage());
            
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

        // ✅ PERBAIKAN: Menggunakan route yang benar dari web.php
        return redirect()->route('admin.products.index')
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

        // ✅ PERBAIKAN: Menggunakan route yang benar dari web.php
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function deleteProduct(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        // ✅ PERBAIKAN: Menggunakan route yang benar dari web.php
        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function showImportForm()
    {
        return view('admin.products.import');
    }

    public function importProducts(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
            'skip_header' => 'nullable|boolean',
            'update_existing' => 'nullable|boolean',
        ]);

        try {
            $file = $request->file('file');
            $skipHeader = $request->boolean('skip_header', true);
            $updateExisting = $request->boolean('update_existing', false);
            
            // Deteksi tipe file
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Tentukan reader berdasarkan ekstensi
            if ($extension === 'csv') {
                $reader = IOFactory::createReader('Csv');
                $reader->setDelimiter(',');
                $reader->setEnclosure('"');
                $reader->setSheetIndex(0);
            } elseif ($extension === 'xls') {
                $reader = IOFactory::createReader('Xls');
            } else {
                $reader = IOFactory::createReader('Xlsx');
            }
            
            // Load spreadsheet
            $spreadsheet = $reader->load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Skip header jika diinginkan
            if ($skipHeader) {
                array_shift($rows);
            }
            
            $imported = 0;
            $updated = 0;
            $errors = [];
            
            foreach ($rows as $index => $row) {
                $rowNumber = $index + ($skipHeader ? 2 : 1); // +2 karena Excel mulai dari 1 dan header
                
                // Skip baris kosong
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Mapping kolom - sesuaikan dengan struktur file
                $data = [
                    'name' => isset($row[0]) ? trim($row[0]) : '',
                    'description' => isset($row[1]) ? trim($row[1]) : '',
                    'price' => isset($row[2]) ? $this->parsePrice($row[2]) : 0,
                    'stock' => isset($row[3]) ? intval($row[3]) : 0,
                ];
                
                // Validasi data
                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'price' => 'required|numeric|min:0',
                    'stock' => 'required|integer|min:0',
                ]);
                
                if ($validator->fails()) {
                    $errors[] = "Baris {$rowNumber}: " . implode(', ', $validator->errors()->all());
                    continue;
                }
                
                // Cek apakah produk sudah ada
                $existingProduct = Product::where('name', $data['name'])->first();
                
                if ($existingProduct && $updateExisting) {
                    // Update produk yang sudah ada
                    $existingProduct->update($data);
                    $updated++;
                } elseif (!$existingProduct) {
                    // Buat produk baru
                    Product::create($data);
                    $imported++;
                }
                // Jika produk sudah ada tapi tidak diupdate, skip
            }
            
            // Siapkan pesan hasil
            $message = "Import selesai. ";
            $message .= "Ditambahkan: {$imported} produk. ";
            $message .= "Diupdate: {$updated} produk. ";
            
            if (!empty($errors)) {
                $errorCount = count($errors);
                $message .= "Gagal: {$errorCount} baris. ";
                
                // Simpan error ke session untuk ditampilkan
                session()->flash('import_errors', $errors);
            }
            
            // ✅ PERBAIKAN: Menggunakan route yang benar dari web.php
            return redirect()->route('admin.products.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            \Log::error('Import error: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            
            return redirect()->back()
                ->with('error', 'Error importing file: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Parse price dari berbagai format
     */
    private function parsePrice($value)
    {
        if (is_numeric($value)) {
            return floatval($value);
        }
        
        // Hapus karakter non-numeric kecuali titik dan koma
        $cleaned = preg_replace('/[^0-9,.]/', '', $value);
        
        // Ganti koma dengan titik untuk decimal
        $cleaned = str_replace(',', '.', $cleaned);
        
        // Hapus titik yang berlebihan (misal: 1.000.000 -> 1000000)
        $lastDot = strrpos($cleaned, '.');
        if ($lastDot !== false) {
            $beforeLast = substr($cleaned, 0, $lastDot);
            $afterLast = substr($cleaned, $lastDot + 1);
            $beforeLast = str_replace('.', '', $beforeLast);
            $cleaned = $beforeLast . '.' . $afterLast;
        }
        
        return floatval($cleaned);
    }

    /**
     * Download template untuk import
     */
    public function downloadTemplate()
    {
        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Header
            $headers = ['Name', 'Description', 'Price', 'Stock'];
            $sheet->fromArray($headers, NULL, 'A1');
            
            // Contoh data
            $examples = [
                ['Laptop Gaming', 'Laptop untuk gaming dengan spesifikasi tinggi', 15000000, 50],
                ['Mouse Wireless', 'Mouse wireless dengan baterai tahan lama', 250000, 100],
                ['Keyboard Mechanical', 'Keyboard mechanical dengan switch merah', 800000, 30],
            ];
            $sheet->fromArray($examples, NULL, 'A2');
            
            // Style header
            $headerStyle = [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E8E8E8']
                ]
            ];
            $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);
            
            // Auto size columns
            foreach (range('A', 'D') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
            
            // Set response
            $filename = 'template_import_produk_' . date('Ymd_His') . '.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error generating template: ' . $e->getMessage());
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

    public function destroyUser(User $user)
    {
        // Cegah penghapusan akun sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }
        
        // Cegah penghapusan admin terakhir
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus admin terakhir.');
        }
        
        try {
            $userName = $user->name;
            $user->delete();
            
            // ✅ PERBAIKAN: Menggunakan route yang benar dari web.php
            return redirect()->route('admin.users.index')
                ->with('success', "Pengguna '{$userName}' berhasil dihapus.");
                
        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus pengguna.');
        }
    }
}