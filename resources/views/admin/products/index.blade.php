@extends('layouts.app')

@section('title', 'Kelola Produk - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-boxes mr-3"></i>Kelola Produk
                </h1>
                <p class="text-gray-600 mt-2">Kelola semua produk yang tersedia di toko.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.products.create') }}" 
                   class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-plus mr-2"></i>Tambah Produk Baru
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500">
                <div class="text-sm text-gray-600">Total Produk</div>
                <div class="text-2xl font-bold text-gray-800">{{ $products->total() }}</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
                <div class="text-sm text-gray-600">Tersedia</div>
                <div class="text-2xl font-bold text-green-600">
                    {{ App\Models\Product::where('stock', '>', 0)->count() }}
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-500">
                <div class="text-sm text-gray-600">Stok Sedikit</div>
                <div class="text-2xl font-bold text-yellow-600">
                    {{ App\Models\Product::where('stock', '<', 10)->where('stock', '>', 0)->count() }}
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-red-500">
                <div class="text-sm text-gray-600">Habis</div>
                <div class="text-2xl font-bold text-red-600">
                    {{ App\Models\Product::where('stock', 0)->count() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <!-- ✅ PERBAIKAN: Ubah dari route('admin.products') ke route('admin.products.index') -->
        <form action="{{ route('admin.products.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-2"></i>Cari Produk
                    </label>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Nama atau deskripsi...">
                </div>

                <!-- Stock Filter -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-filter mr-2"></i>Filter Stok
                    </label>
                    <select name="stock" 
                            id="stock"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Semua Stok</option>
                        <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Stok Sedikit (< 10)</option>
                        <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Stok Habis (0)</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sort mr-2"></i>Urutkan
                    </label>
                    <select name="sort" 
                            id="sort"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Harga (Rendah-Tinggi)</option>
                        <option value="stock" {{ request('sort') == 'stock' ? 'selected' : '' }}>Stok (Sedikit-Banyak)</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-filter mr-2"></i>Terapkan Filter
                </button>
                <!-- ✅ PERBAIKAN: Ubah dari route('admin.products') ke route('admin.products.index') -->
                <a href="{{ route('admin.products.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-redo mr-2"></i>Reset Filter
                </a>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-800">
                    <i class="fas fa-list mr-2"></i>Daftar Produk
                </h2>
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }}
                </div>
            </div>
        </div>

        @if($products->count() > 0)
            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stok
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $product->name }}"
                                                 class="h-12 w-12 rounded-lg object-cover mr-4">
                                        @else
                                            <div class="h-12 w-12 bg-gray-200 rounded-lg flex items-center justify-center mr-4">
                                                <i class="fas fa-box text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500 truncate max-w-xs">
                                                {{ Str::limit($product->description, 50) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-lg font-bold {{ $product->stock == 0 ? 'text-red-600' : ($product->stock < 10 ? 'text-yellow-600' : 'text-green-600') }}">
                                        {{ $product->stock }}
                                    </div>
                                    <div class="text-xs text-gray-500">unit</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->stock == 0)
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Habis
                                        </span>
                                    @elseif($product->stock < 10)
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Stok Sedikit
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Tersedia
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50 transition"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                                onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')"
                                                class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50 transition"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <!-- Hidden Delete Form -->
                                        <!-- ✅ PERBAIKAN: Ubah dari route('admin.products.delete') ke route('admin.products.destroy') -->
                                        <form id="delete-form-{{ $product->id }}" 
                                              action="{{ route('admin.products.delete', $product) }}" 
                                              method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $products->withQueryString()->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-box-open text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada produk</h3>
                <p class="text-gray-600 mb-6">Mulai tambahkan produk pertama Anda.</p>
                <a href="{{ route('admin.products.create') }}" 
                   class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center inline-flex">
                    <i class="fas fa-plus mr-2"></i>Tambah Produk Pertama
                </a>
            </div>
        @endif
    </div>

    <!-- Import Button -->
    <div class="mt-6 text-center">
        <a href="{{ route('admin.products.import') }}" 
           class="inline-flex items-center text-green-600 hover:text-green-800">
            <i class="fas fa-file-import mr-2"></i>Import Produk dari Excel
        </a>
    </div>
</div>

<!-- JavaScript for Delete Confirmation -->
<script>
function confirmDelete(productId, productName) {
    if (confirm(`Apakah Anda yakin ingin menghapus produk "${productName}"?`)) {
        document.getElementById(`delete-form-${productId}`).submit();
    }
}
</script>
@endsection