@extends('layouts.app')

@section('title', 'Manajemen Produk - Toko Online')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-boxes mr-3"></i>Manajemen Produk
                </h1>
                <p class="text-gray-600 mt-2">Kelola produk dan inventori toko Anda</p>
            </div>
            
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('admin.products.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Tambah Produk
                </a>
                <a href="{{ route('admin.products.import') }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-file-import mr-2"></i>Import Excel
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="{{ route('admin.products') }}" class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari produk..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                </div>
            </div>
            
            <div class="flex space-x-4 md:ml-4">
                <select name="stock" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Stok</option>
                    <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Stok Sedikit</option>
                    <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Habis</option>
                </select>
                
                <select name="sort" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Terbaru</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Harga</option>
                    <option value="stock" {{ request('sort') === 'stock' ? 'selected' : '' }}>Stok</option>
                </select>
                
                <button type="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                
                <a href="{{ route('admin.products') }}" 
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($products->count() > 0)
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
                                        <div class="h-12 w-12 flex-shrink-0">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" 
                                                     alt="{{ $product->name }}"
                                                     class="h-12 w-12 object-cover rounded">
                                            @else
                                                <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
                                                    <i class="fas fa-box text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ $product->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->stock }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->stock == 0)
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                            Habis
                                        </span>
                                    @elseif($product->stock < 10)
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                            Stok Sedikit
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                            Tersedia
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('admin.products.edit', $product) }}" 
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('product.show', $product) }}" 
                                           target="_blank"
                                           class="text-green-600 hover:text-green-900"
                                           title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.products.delete', $product) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
                {{ $products->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Produk</h3>
                <p class="text-gray-500 mb-6">Tambahkan produk pertama Anda</p>
                <div class="space-x-4">
                    <a href="{{ route('admin.products.create') }}" 
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>Tambah Produk
                    </a>
                    <a href="{{ route('admin.products.import') }}" 
                       class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                        <i class="fas fa-file-import mr-2"></i>Import Excel
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Stats -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-box text-blue-600"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Total Produk</div>
                    <div class="text-2xl font-bold text-gray-800">{{ $products->total() }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Stok Tersedia</div>
                    <div class="text-2xl font-bold text-gray-800">
                        {{ $products->where('stock', '>', 0)->count() }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Stok Habis</div>
                    <div class="text-2xl font-bold text-gray-800">
                        {{ $products->where('stock', 0)->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection