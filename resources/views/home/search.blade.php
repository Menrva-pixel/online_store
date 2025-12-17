@extends('layouts.app')

@section('title', 'Pencarian: ' . $query . ' - Toko Online')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-700 font-medium">Pencarian</span>
                </div>
            </li>
        </ol>
    </nav>
    
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            Hasil Pencarian: "{{ $query }}"
        </h1>
        <p class="text-gray-600">
            Ditemukan {{ $products->total() }} produk
        </p>
    </div>
    
    <!-- Search Form -->
    <form action="{{ route('search') }}" method="GET" class="mb-8">
        <div class="relative max-w-lg">
            <input type="text" 
                   name="query" 
                   value="{{ $query }}"
                   placeholder="Cari produk lain..."
                   class="w-full px-6 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">
            <button type="submit" class="absolute right-4 top-3.5 text-gray-400 hover:text-blue-600 text-xl">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
    
    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    <a href="{{ route('product.show', $product) }}">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-box text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                    </a>
                    
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2 truncate">
                            <a href="{{ route('product.show', $product) }}" class="hover:text-blue-600">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                            {{ $product->description ?: 'Tidak ada deskripsi' }}
                        </p>
                        
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-xl font-bold text-blue-600">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                <div class="text-sm text-gray-500">
                                    Stok: {{ $product->stock }}
                                </div>
                            </div>
                            
                            @auth
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" 
                                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <!-- No Results -->
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <div class="mb-6">
                <i class="fas fa-search text-gray-300 text-8xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">
                Produk tidak ditemukan
            </h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                Tidak ada produk yang sesuai dengan pencarian "{{ $query }}".
                Coba dengan kata kunci lain atau periksa ejaan.
            </p>
            <div class="space-x-4">
                <a href="{{ route('home') }}" 
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-home mr-2"></i>Kembali ke Home
                </a>
                <a href="{{ route('search') }}?query=" 
                   class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition duration-300">
                    <i class="fas fa-redo mr-2"></i>Cari Semua Produk
                </a>
            </div>
        </div>
    @endif
</div>
@endsection