@extends('layouts.app')

@section('title', 'Home - Toko Online')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden">
    <div class="absolute inset-0 -z-10 bg-gradient-to-br from-emerald-50 via-amber-50 to-cyan-50"></div>
    <div class="max-w-7xl mx-auto px-4 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-white/70 px-4 py-2 text-sm font-semibold text-emerald-700 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Kurasi produk terbaik setiap minggu
                </div>
                <h1 class="mt-5 text-4xl md:text-5xl font-bold text-slate-900 leading-tight">
                    Belanja lebih rapi, lebih cepat, dan terasa premium
                </h1>
                <p class="mt-4 text-lg text-slate-600">
                    Toko Online menyajikan koleksi produk pilihan dengan proses belanja yang aman,
                    transparan, dan nyaman untuk semua perangkat.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="#products" class="rounded-full bg-emerald-600 px-6 py-3 text-white font-semibold shadow-sm hover:bg-emerald-700">
                        Mulai Belanja
                    </a>
                    <a href="{{ route('search') }}" class="rounded-full border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 hover:border-slate-400">
                        Cari Produk
                    </a>
                </div>
                <div class="mt-8 grid grid-cols-2 gap-4 text-sm text-slate-600">
                    <div class="rounded-2xl border border-emerald-100 bg-white/70 px-4 py-3">
                        <div class="font-semibold text-slate-900">24/7 Support</div>
                        <div>Respon cepat untuk setiap pertanyaan</div>
                    </div>
                    <div class="rounded-2xl border border-amber-100 bg-white/70 px-4 py-3">
                        <div class="font-semibold text-slate-900">Pengiriman Cepat</div>
                        <div>Notifikasi status order real-time</div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="absolute -top-6 -right-6 h-24 w-24 rounded-3xl bg-emerald-200/60 blur-sm"></div>
                <div class="absolute -bottom-6 -left-6 h-28 w-28 rounded-3xl bg-amber-200/60 blur-sm"></div>
                <div class="rounded-3xl border border-white/60 bg-white/70 p-4 shadow-xl backdrop-blur">
                    <img src="https://cdn.pixabay.com/photo/2016/11/29/12/30/phone-1869510_1280.jpg"
                         alt="Online Shopping"
                         class="h-full w-full rounded-2xl object-cover">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-12 bg-white/60" id="products">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-end justify-between gap-6 flex-wrap mb-8">
            <div>
                <h2 class="text-3xl font-bold text-slate-900">Produk Unggulan</h2>
                <p class="mt-2 text-slate-600">Pilihan favorit dengan kualitas terbaik.</p>
            </div>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <i class="fas fa-star text-amber-400"></i>
                Kurasi mingguan dari tim kami
            </div>
        </div>
        
        @if($featuredProducts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <div class="group rounded-2xl border border-slate-200 bg-white/80 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="h-48 w-full rounded-t-2xl object-cover">
                            @else
                                <div class="flex h-48 w-full items-center justify-center rounded-t-2xl bg-slate-100">
                                    <i class="fas fa-box text-slate-300 text-4xl"></i>
                                </div>
                            @endif
                            
                            @if($product->stock <= 0)
                                <div class="absolute top-3 right-3 rounded-full bg-rose-500 px-3 py-1 text-xs font-semibold text-white">
                                    Habis
                                </div>
                            @elseif($product->stock <= 10)
                                <div class="absolute top-3 right-3 rounded-full bg-amber-500 px-3 py-1 text-xs font-semibold text-white">
                                    Stok Terbatas
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-lg text-slate-900 truncate">{{ $product->name }}</h3>
                            <p class="text-slate-600 text-sm mt-1 line-clamp-2">
                                {{ $product->description ?: 'Tidak ada deskripsi' }}
                            </p>
                            <div class="mt-4 flex items-center justify-between">
                                <div>
                                    <div class="text-xl font-bold text-emerald-700">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-slate-500">Stok: {{ $product->stock }}</div>
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
                            
                            <div class="mt-4">
                                <a href="{{ route('product.show', $product) }}"
                                   class="text-emerald-700 hover:text-emerald-800 text-sm font-semibold">
                                    Lihat Detail ->
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-box-open text-slate-300 text-6xl mb-4"></i>
                <p class="text-slate-500 text-lg">Belum ada produk tersedia.</p>
            </div>
        @endif
    </div>
</section>

<!-- All Products -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-wrap items-center justify-between gap-6 mb-8">
            <div>
                <h2 class="text-3xl font-bold text-slate-900">Semua Produk</h2>
                <p class="mt-1 text-slate-600">Jelajahi seluruh koleksi yang tersedia.</p>
            </div>
            
            <!-- Search Form -->
            <form action="{{ route('search') }}" method="GET" class="w-full sm:w-80">
                <div class="relative">
                    <input type="text"
                           name="query"
                           placeholder="Cari produk..."
                           class="w-full rounded-full border border-slate-200 bg-white px-5 py-3 pr-12 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200">
                    <button type="submit" class="absolute right-4 top-3 text-slate-400 hover:text-emerald-700">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        
        @if($allProducts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($allProducts as $product)
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
                            <h3 class="font-semibold text-lg text-slate-900 truncate">
                                <a href="{{ route('product.show', $product) }}" class="hover:text-emerald-700">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <div class="mt-4 flex items-center justify-between">
                                <div class="text-xl font-bold text-emerald-700">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
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
                {{ $allProducts->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-search text-slate-300 text-6xl mb-4"></i>
                <p class="text-slate-500 text-lg">Tidak ada produk ditemukan.</p>
                <a href="{{ route('home') }}" class="text-emerald-700 hover:text-emerald-800 mt-2 inline-block">
                    Kembali ke halaman utama
                </a>
            </div>
        @endif
    </div>
</section>

<!-- How It Works Section -->
<section class="py-12 bg-white/60">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col items-center text-center mb-10">
            <h2 class="text-3xl font-bold text-slate-900">Cara Berbelanja di Toko Kami</h2>
            <p class="mt-2 text-slate-600 max-w-2xl">
                Proses belanja dibuat ringkas agar Anda bisa fokus memilih produk terbaik.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="rounded-2xl border border-slate-200 bg-white/80 p-6 text-center shadow-sm">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                    <i class="fas fa-search text-xl"></i>
                </div>
                <h3 class="font-semibold text-lg">1. Cari Produk</h3>
                <p class="text-slate-600 mt-1">Temukan produk yang Anda inginkan</p>
            </div>
            
            <div class="rounded-2xl border border-slate-200 bg-white/80 p-6 text-center shadow-sm">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                    <i class="fas fa-cart-plus text-xl"></i>
                </div>
                <h3 class="font-semibold text-lg">2. Tambah ke Keranjang</h3>
                <p class="text-slate-600 mt-1">Atur jumlah produk sesuai kebutuhan</p>
            </div>
            
            <div class="rounded-2xl border border-slate-200 bg-white/80 p-6 text-center shadow-sm">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-700">
                    <i class="fas fa-credit-card text-xl"></i>
                </div>
                <h3 class="font-semibold text-lg">3. Bayar & Upload Bukti</h3>
                <p class="text-slate-600 mt-1">Pembayaran diverifikasi dua lapis</p>
            </div>
            
            <div class="rounded-2xl border border-slate-200 bg-white/80 p-6 text-center shadow-sm">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                    <i class="fas fa-truck text-xl"></i>
                </div>
                <h3 class="font-semibold text-lg">4. Barang Dikirim</h3>
                <p class="text-slate-600 mt-1">Status pengiriman selalu ter-update</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="rounded-3xl bg-gradient-to-r from-emerald-600 to-cyan-600 p-10 text-white shadow-xl">
            <div class="flex flex-col items-center text-center">
                <h2 class="text-3xl font-bold mb-3">Siap Mulai Berbelanja?</h2>
                <p class="text-lg text-white/90 mb-6 max-w-2xl">
                    Bergabung dengan ribuan pelanggan yang menikmati pengalaman belanja yang lebih modern.
                </p>
                
                @auth
                    <a href="#products" class="rounded-full bg-white px-8 py-3 font-semibold text-emerald-700 shadow-sm hover:bg-slate-50">
                        Lanjutkan Belanja
                    </a>
                @else
                    <div class="flex flex-wrap gap-3 justify-center">
                        <a href="{{ route('register') }}" class="rounded-full bg-white px-8 py-3 font-semibold text-emerald-700 shadow-sm hover:bg-slate-50">
                            Daftar Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="rounded-full border border-white/70 px-8 py-3 font-semibold text-white hover:bg-white/10">
                            Masuk
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection
