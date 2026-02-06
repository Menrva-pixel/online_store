@extends('layouts.app')

@section('title', $product->name . ' - Toko Online')

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
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                    <span>Produk</span>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                    <span class="text-slate-900 font-semibold">{{ $product->name }}</span>
                </div>
            </li>
        </ol>
    </nav>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-sm">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-auto rounded-2xl object-cover">
            @else
                <div class="w-full h-96 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-box text-slate-300 text-7xl"></i>
                </div>
            @endif
            
            <!-- Stock Status -->
            @if($product->stock <= 0)
                <div class="mt-4 p-3 bg-rose-50 text-rose-700 rounded-2xl border border-rose-100">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Produk ini saat ini habis.
                </div>
            @elseif($product->stock <= 10)
                <div class="mt-4 p-3 bg-amber-50 text-amber-700 rounded-2xl border border-amber-100">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Stok terbatas, hanya tersisa {{ $product->stock }} barang.
                </div>
            @else
                <div class="mt-4 p-3 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100">
                    <i class="fas fa-check-circle mr-2"></i>
                    Stok tersedia: {{ $product->stock }} barang.
                </div>
            @endif
        </div>
        
        <!-- Product Details -->
        <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-sm">
            <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $product->name }}</h1>
            
            <div class="mb-4">
                <span class="text-4xl font-bold text-emerald-700">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
            </div>
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-700 mb-2">Deskripsi Produk:</h3>
                <p class="text-slate-600">
                    {{ $product->description ?: 'Tidak ada deskripsi tersedia untuk produk ini.' }}
                </p>
            </div>
            
            <!-- Cart Form -->
            @auth
                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-6">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-slate-700 mb-2">
                                Jumlah:
                            </label>
                            <div class="flex items-center">
                                <button type="button"
                                        onclick="decrementQuantity()"
                                        class="bg-slate-100 text-slate-700 px-3 py-2 rounded-l-full border border-slate-200">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number"
                                       id="quantity"
                                       name="quantity"
                                       value="{{ $inCart ? $cartQuantity : 1 }}"
                                       min="1"
                                       max="{{ $product->stock }}"
                                       class="w-24 text-center border-y border-slate-200 py-2 bg-white">
                                <button type="button"
                                        onclick="incrementQuantity()"
                                        class="bg-slate-100 text-slate-700 px-3 py-2 rounded-r-full border border-slate-200">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <p class="text-sm text-slate-500 mt-2">
                                Maksimal {{ $product->stock }} barang
                            </p>
                        </div>
                        
                        <div class="flex flex-wrap gap-3">
                            @if($inCart)
                                <button type="submit"
                                        class="flex-1 rounded-full bg-amber-500 text-white px-6 py-3 font-semibold hover:bg-amber-600">
                                    <i class="fas fa-sync-alt mr-2"></i>
                                    Update Keranjang
                                </button>
                            @else
                                <button type="submit"
                                        class="flex-1 rounded-full bg-emerald-600 text-white px-6 py-3 font-semibold hover:bg-emerald-700">
                                    <i class="fas fa-cart-plus mr-2"></i>
                                    Tambah ke Keranjang
                                </button>
                            @endif
                            
                            <a href="{{ route('cart.index') }}"
                               class="flex-1 rounded-full bg-slate-900 text-white px-6 py-3 text-center font-semibold hover:bg-slate-800">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Lihat Keranjang
                            </a>
                        </div>
                    </form>
                @else
                    <div class="p-5 bg-slate-50 rounded-2xl text-center border border-slate-200">
                        <i class="fas fa-times-circle text-rose-500 text-3xl mb-3"></i>
                        <p class="text-slate-700 font-medium">Produk saat ini tidak tersedia</p>
                        <p class="text-slate-500 text-sm mt-1">Silakan kembali lagi nanti</p>
                    </div>
                @endif
            @else
                <div class="p-5 bg-emerald-50 rounded-2xl text-center border border-emerald-100">
                    <i class="fas fa-info-circle text-emerald-600 text-3xl mb-3"></i>
                    <p class="text-slate-700 font-medium">Login untuk membeli produk ini</p>
                    <div class="mt-4 flex flex-wrap justify-center gap-3">
                        <a href="{{ route('login') }}"
                           class="rounded-full bg-emerald-600 text-white px-6 py-2 font-semibold hover:bg-emerald-700">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="rounded-full bg-slate-900 text-white px-6 py-2 font-semibold hover:bg-slate-800">
                            <i class="fas fa-user-plus mr-2"></i>Daftar
                        </a>
                    </div>
                </div>
            @endauth
            
            <!-- Product Info -->
            <div class="mt-6 pt-6 border-t border-slate-200">
                <h3 class="text-lg font-semibold text-slate-700 mb-3">Informasi Produk:</h3>
                <ul class="space-y-2 text-slate-600">
                    <li class="flex items-center">
                        <i class="fas fa-box text-slate-400 mr-3 w-5"></i>
                        <span>Stok: <strong>{{ $product->stock }}</strong> barang</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-calendar text-slate-400 mr-3 w-5"></i>
                        <span>Ditambahkan: {{ $product->created_at->format('d M Y') }}</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-tag text-slate-400 mr-3 w-5"></i>
                        <span>Kode Produk: #PROD{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Back to Products -->
    <div class="mt-8 text-center">
        <a href="{{ route('home') }}"
           class="inline-flex items-center text-emerald-700 hover:text-emerald-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Produk
        </a>
    </div>
</div>

@push('scripts')
<script>
function incrementQuantity() {
    const quantityInput = document.getElementById('quantity');
    const max = parseInt(quantityInput.max);
    const current = parseInt(quantityInput.value);
    
    if (current < max) {
        quantityInput.value = current + 1;
    }
}

function decrementQuantity() {
    const quantityInput = document.getElementById('quantity');
    const min = parseInt(quantityInput.min);
    const current = parseInt(quantityInput.value);
    
    if (current > min) {
        quantityInput.value = current - 1;
    }
}
</script>
@endpush
@endsection
