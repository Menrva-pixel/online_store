@extends('layouts.app')

@section('title', $product->name . ' - Toko Online')

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
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">Produk</span>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-700 font-medium">{{ $product->name }}</span>
                </div>
            </li>
        </ol>
    </nav>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}"
                     class="w-full h-auto rounded-lg">
            @else
                <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box text-gray-400 text-8xl"></i>
                </div>
            @endif
            
            <!-- Stock Status -->
            @if($product->stock <= 0)
                <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Produk ini saat ini habis.
                </div>
            @elseif($product->stock <= 10)
                <div class="mt-4 p-3 bg-yellow-100 text-yellow-700 rounded-lg">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Stok terbatas, hanya tersisa {{ $product->stock }} barang.
                </div>
            @else
                <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    Stok tersedia: {{ $product->stock }} barang.
                </div>
            @endif
        </div>
        
        <!-- Product Details -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
            
            <div class="mb-4">
                <span class="text-4xl font-bold text-blue-600">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
            </div>
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Deskripsi Produk:</h3>
                <p class="text-gray-600">
                    {{ $product->description ?: 'Tidak ada deskripsi tersedia untuk produk ini.' }}
                </p>
            </div>
            
            <!-- Cart Form -->
            @auth
                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-6">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah:
                            </label>
                            <div class="flex items-center">
                                <button type="button" 
                                        onclick="decrementQuantity()" 
                                        class="bg-gray-200 text-gray-700 px-3 py-2 rounded-l-lg">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="{{ $inCart ? $cartQuantity : 1 }}" 
                                       min="1" 
                                       max="{{ $product->stock }}"
                                       class="w-20 text-center border-y border-gray-300 py-2">
                                <button type="button" 
                                        onclick="incrementQuantity()" 
                                        class="bg-gray-200 text-gray-700 px-3 py-2 rounded-r-lg">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">
                                Maksimal {{ $product->stock }} barang
                            </p>
                        </div>
                        
                        <div class="flex space-x-4">
                            @if($inCart)
                                <button type="submit" 
                                        class="flex-1 bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition duration-300">
                                    <i class="fas fa-sync-alt mr-2"></i>
                                    Update Keranjang
                                </button>
                            @else
                                <button type="submit" 
                                        class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                                    <i class="fas fa-cart-plus mr-2"></i>
                                    Tambah ke Keranjang
                                </button>
                            @endif
                            
                            <a href="{{ route('cart.index') }}" 
                               class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-300 text-center">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Lihat Keranjang
                            </a>
                        </div>
                    </form>
                @else
                    <div class="p-4 bg-gray-100 rounded-lg text-center">
                        <i class="fas fa-times-circle text-red-500 text-3xl mb-3"></i>
                        <p class="text-gray-700 font-medium">Produk saat ini tidak tersedia</p>
                        <p class="text-gray-500 text-sm mt-1">Silakan kembali lagi nanti</p>
                    </div>
                @endif
            @else
                <div class="p-4 bg-blue-50 rounded-lg text-center">
                    <i class="fas fa-info-circle text-blue-500 text-3xl mb-3"></i>
                    <p class="text-gray-700 font-medium">Login untuk membeli produk ini</p>
                    <div class="mt-4 space-x-4">
                        <a href="{{ route('login') }}" 
                           class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" 
                           class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-300">
                            <i class="fas fa-user-plus mr-2"></i>Daftar
                        </a>
                    </div>
                </div>
            @endauth
            
            <!-- Product Info -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Informasi Produk:</h3>
                <ul class="space-y-2">
                    <li class="flex items-center">
                        <i class="fas fa-box text-gray-400 mr-3 w-5"></i>
                        <span class="text-gray-600">Stok: <strong>{{ $product->stock }}</strong> barang</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-calendar text-gray-400 mr-3 w-5"></i>
                        <span class="text-gray-600">Ditambahkan: {{ $product->created_at->format('d M Y') }}</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-tag text-gray-400 mr-3 w-5"></i>
                        <span class="text-gray-600">Kode Produk: #PROD{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Back to Products -->
    <div class="mt-8 text-center">
        <a href="{{ route('home') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
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