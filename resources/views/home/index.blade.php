@extends('layouts.app')

@section('title', 'Home - Toko Online')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="md:w-1/2 mb-8 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Belanja Online Mudah & Aman
                </h1>
                <p class="text-xl mb-6">
                    Temukan berbagai produk berkualitas dengan harga terbaik.
                    Pengiriman cepat dan pelayanan customer service 24/7.
                </p>
                <a href="#products" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                    Mulai Belanja
                </a>
            </div>
            <div class="md:w-1/2">
                <img src="https://cdn.pixabay.com/photo/2016/11/29/12/30/phone-1869510_1280.jpg" 
                     alt="Online Shopping" 
                     class="rounded-lg shadow-2xl">
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-12 bg-gray-50" id="products">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">
            Produk Unggulan
        </h2>
        
        @if($featuredProducts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        <div class="relative">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                            
                            @if($product->stock <= 0)
                                <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm">
                                    Habis
                                </div>
                            @elseif($product->stock <= 10)
                                <div class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded text-sm">
                                    Stok Terbatas
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-2 truncate">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                {{ $product->description ?: 'Tidak ada deskripsi' }}
                            </p>
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-2xl font-bold text-blue-600">
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
                            
                            <div class="mt-4">
                                <a href="{{ route('product.show', $product) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                    Lihat Detail →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada produk tersedia.</p>
            </div>
        @endif
    </div>
</section>

<!-- All Products -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">
                Semua Produk
            </h2>
            
            <!-- Search Form -->
            <form action="{{ route('search') }}" method="GET" class="w-64">
                <div class="relative">
                    <input type="text" 
                           name="query" 
                           placeholder="Cari produk..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-blue-600">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        
        @if($allProducts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($allProducts as $product)
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
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="text-xl font-bold text-blue-600">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
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
                {{ $allProducts->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Tidak ada produk ditemukan.</p>
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                    Kembali ke halaman utama
                </a>
            </div>
        @endif
    </div>
</section>

<!-- How It Works Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center">
            Cara Berbelanja di Toko Kami
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-blue-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">1. Cari Produk</h3>
                <p class="text-gray-600">Temukan produk yang Anda inginkan</p>
            </div>
            
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-cart-plus text-blue-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">2. Tambah ke Keranjang</h3>
                <p class="text-gray-600">Masukkan produk ke keranjang belanja</p>
            </div>
            
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-credit-card text-blue-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">3. Bayar & Upload Bukti</h3>
                <p class="text-gray-600">Lakukan pembayaran dan upload bukti</p>
            </div>
            
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-truck text-blue-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">4. Barang Dikirim</h3>
                <p class="text-gray-600">Barang akan diproses dan dikirim</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-12 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Siap Mulai Berbelanja?</h2>
        <p class="text-xl mb-6">Bergabunglah dengan ribuan pelanggan puas kami</p>
        
        @auth
            <a href="#products" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                Lanjutkan Belanja
            </a>
        @else
            <div class="space-x-4">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                    Masuk
                </a>
            </div>
        @endauth
    </div>
</section>
@endsection