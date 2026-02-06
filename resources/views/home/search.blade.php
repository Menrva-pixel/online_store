@extends('layouts.app')

@section('title', 'Pencarian: ' . $query . ' - Toko Online')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-slate-500">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="text-emerald-700 hover:text-emerald-800">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                    <span class="text-slate-700 font-medium">Pencarian</span>
                </div>
            </li>
        </ol>
    </nav>
    
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">
            Hasil Pencarian: "{{ $query }}"
        </h1>
        <p class="text-slate-600">
            Ditemukan {{ $products->total() }} produk
        </p>
    </div>
    
    <!-- Search Form -->
    <form action="{{ route('search') }}" method="GET" class="mb-8">
        <div class="relative max-w-xl">
            <input type="text"
                   name="query"
                   value="{{ $query }}"
                   placeholder="Cari produk lain..."
                   class="w-full rounded-full border border-slate-200 bg-white px-6 py-3 pr-12 text-base focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
            <button type="submit" class="absolute right-4 top-3.5 text-slate-400 hover:text-emerald-700 text-xl">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
    
    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="group rounded-2xl border border-slate-200 bg-white/80 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <a href="{{ route('product.show', $product) }}">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="h-48 w-full rounded-t-2xl object-cover">
                        @else
                            <div class="flex h-48 w-full items-center justify-center rounded-t-2xl bg-slate-100">
                                <i class="fas fa-box text-slate-300 text-4xl"></i>
                            </div>
                        @endif
                    </a>
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2 truncate">
                            <a href="{{ route('product.show', $product) }}" class="hover:text-emerald-700">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <p class="text-slate-600 text-sm mb-3 line-clamp-2">
                            {{ $product->description ?: 'Tidak ada deskripsi' }}
                        </p>
                        
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-xl font-bold text-emerald-700">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                <div class="text-xs text-slate-500">
                                    Stok: {{ $product->stock }}
                                </div>
                            </div>
                            
                            @auth
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit"
                                                class="rounded-full bg-emerald-600 px-4 py-2 text-white shadow-sm hover:bg-emerald-700">
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
        <div class="text-center py-12 bg-white/80 rounded-3xl border border-slate-200 shadow-sm">
            <div class="mb-6">
                <i class="fas fa-search text-slate-300 text-7xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-700 mb-2">
                Produk tidak ditemukan
            </h3>
            <p class="text-slate-500 mb-6 max-w-md mx-auto">
                Tidak ada produk yang sesuai dengan pencarian "{{ $query }}".
                Coba dengan kata kunci lain atau periksa ejaan.
            </p>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('home') }}"
                   class="rounded-full bg-emerald-600 text-white px-6 py-3 font-semibold hover:bg-emerald-700">
                    <i class="fas fa-home mr-2"></i>Kembali ke Home
                </a>
                <a href="{{ route('search') }}?query="
                   class="rounded-full border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 hover:border-slate-400">
                    <i class="fas fa-redo mr-2"></i>Cari Semua Produk
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
