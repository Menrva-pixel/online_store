@extends('layouts.app')

@section('title', 'Keranjang Belanja - Toko Online')

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
                    <span class="text-slate-700 font-medium">Keranjang Belanja</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <h1 class="text-3xl font-bold text-slate-900">
            <i class="fas fa-shopping-cart mr-3"></i>Keranjang Belanja
        </h1>
        <a href="{{ route('home') }}" class="text-sm text-emerald-700 hover:text-emerald-800">
            <i class="fas fa-plus mr-1"></i>Tambah Produk Lain
        </a>
    </div>

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                <div class="rounded-2xl border border-slate-200 bg-white/80 p-4 text-sm text-slate-600">
                    {{ $cartItems->count() }} item di keranjang
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white/80 shadow-sm overflow-hidden">
                    @foreach($cartItems as $item)
                        <div class="border-b border-slate-100 p-6">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <!-- Product Image -->
                                <div class="sm:w-32 sm:h-32 w-full h-48">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-full h-full object-cover rounded-2xl">
                                    @else
                                        <div class="w-full h-full bg-slate-100 rounded-2xl flex items-center justify-center">
                                            <i class="fas fa-box text-slate-300 text-4xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1">
                                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-900 mb-2">
                                                <a href="{{ route('product.show', $item->product) }}"
                                                   class="hover:text-emerald-700">
                                                    {{ $item->product->name }}
                                                </a>
                                            </h3>
                                            
                                            @if($item->product->stock <= 0)
                                                <div class="inline-flex items-center rounded-full bg-rose-50 text-rose-700 px-3 py-1 text-xs font-semibold mb-2">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>Habis
                                                </div>
                                            @elseif($item->product->stock < $item->quantity)
                                                <div class="inline-flex items-center rounded-full bg-amber-50 text-amber-700 px-3 py-1 text-xs font-semibold mb-2">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Stok hanya {{ $item->product->stock }} tersedia
                                                </div>
                                            @endif
                                            
                                            <p class="text-slate-600 text-sm">
                                                {{ Str::limit($item->product->description, 150) }}
                                            </p>
                                        </div>

                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-emerald-700">
                                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </div>
                                            <div class="text-xs text-slate-500">per item</div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-4">
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center">
                                            <span class="text-slate-600 mr-4">Jumlah:</span>
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                                                @csrf
                                                @method('PUT')
                                                <button type="button"
                                                        onclick="decrementQuantity({{ $item->id }})"
                                                        class="bg-slate-100 text-slate-700 px-3 py-2 rounded-l-full border border-slate-200">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number"
                                                       id="quantity-{{ $item->id }}"
                                                       name="quantity"
                                                       value="{{ $item->quantity }}"
                                                       min="1"
                                                       max="{{ $item->product->stock }}"
                                                       class="w-20 text-center border-y border-slate-200 py-2 bg-white quantity-input"
                                                       data-item-id="{{ $item->id }}">
                                                <button type="button"
                                                        onclick="incrementQuantity({{ $item->id }})"
                                                        class="bg-slate-100 text-slate-700 px-3 py-2 rounded-r-full border border-slate-200">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button type="submit"
                                                        class="ml-4 rounded-full bg-emerald-600 text-white px-4 py-2 font-semibold hover:bg-emerald-700 hidden update-btn"
                                                        id="update-btn-{{ $item->id }}">
                                                    <i class="fas fa-sync-alt mr-2"></i>Update
                                                </button>
                                            </form>
                                        </div>

                                        <div class="text-right">
                                            <div class="text-xl font-bold text-slate-900">
                                                Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                            </div>
                                            <form action="{{ route('cart.remove', $item) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-rose-600 hover:text-rose-700 text-sm"
                                                        onclick="return confirm('Hapus item ini dari keranjang?')">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1 space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-sm sticky top-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 border-b border-slate-100 pb-4">
                        <i class="fas fa-receipt mr-2"></i>Ringkasan Pesanan
                    </h2>

                    <div class="space-y-4 mb-6 text-sm text-slate-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-semibold text-slate-900" id="subtotal">
                                Rp {{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }), 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Pengiriman</span>
                            <span class="font-semibold text-slate-900">Rp 15.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pajak (10%)</span>
                            <span class="font-semibold text-slate-900" id="tax">
                                Rp {{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }) * 0.1, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-slate-900">Total</span>
                            <span class="text-2xl font-bold text-emerald-700" id="total">
                                @php
                                    $subtotal = $cartItems->sum(function($item) { return $item->product->price * $item->quantity; });
                                    $tax = $subtotal * 0.1;
                                    $shipping = 15000;
                                    $total = $subtotal + $tax + $shipping;
                                @endphp
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('checkout') }}" method="GET">
                        @csrf
                        <button type="submit"
                                class="w-full rounded-full bg-emerald-600 text-white py-3 px-4 text-lg font-semibold hover:bg-emerald-700">
                            <i class="fas fa-credit-card mr-2"></i>Lanjut ke Pembayaran
                        </button>
                    </form>

                    <div class="mt-6 rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-700">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-shield-alt text-emerald-600 mr-2"></i>
                            <span class="font-medium">Belanja Aman</span>
                        </div>
                        Transaksi Anda dilindungi dengan sistem keamanan terbaik.
                    </div>

                    <div class="mt-6">
                        <h4 class="font-medium text-slate-700 mb-3">
                            <i class="fas fa-credit-card mr-2"></i>Metode Pembayaran:
                        </h4>
                        <div class="grid grid-cols-3 gap-2 text-xs text-slate-600">
                            <div class="bg-slate-100 p-2 rounded-xl text-center">
                                <i class="fas fa-university text-slate-600"></i>
                                <span class="block mt-1">Transfer Bank</span>
                            </div>
                            <div class="bg-slate-100 p-2 rounded-xl text-center">
                                <i class="fas fa-mobile-alt text-slate-600"></i>
                                <span class="block mt-1">E-Wallet</span>
                            </div>
                            <div class="bg-slate-100 p-2 rounded-xl text-center">
                                <i class="fas fa-store text-slate-600"></i>
                                <span class="block mt-1">COD</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-sm">
                    <h3 class="font-bold text-slate-900 mb-4">
                        <i class="fas fa-tag mr-2"></i>Kode Promo
                    </h3>
                    <div class="flex">
                        <input type="text"
                               placeholder="Masukkan kode promo"
                               class="flex-1 border border-slate-200 rounded-l-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                        <button class="bg-emerald-600 text-white px-4 py-2 rounded-r-full hover:bg-emerald-700">
                            Gunakan
                        </button>
                    </div>
                    <p class="text-sm text-slate-500 mt-2">
                        *Kode promo akan diverifikasi saat checkout
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-16">
            <div class="mb-8">
                <div class="inline-flex items-center justify-center p-8 bg-slate-100 rounded-full">
                    <i class="fas fa-shopping-cart text-slate-300 text-7xl"></i>
                </div>
            </div>
            
            <h2 class="text-3xl font-bold text-slate-700 mb-4">Keranjang Belanjamu Kosong</h2>
            <p class="text-slate-500 text-lg mb-8 max-w-md mx-auto">
                Tambahkan beberapa produk ke keranjang dan kembali ke sini untuk melihat ringkasan pesanan.
            </p>
            
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('home') }}"
                   class="inline-block rounded-full bg-emerald-600 text-white px-8 py-3 font-semibold hover:bg-emerald-700">
                    <i class="fas fa-shopping-bag mr-2"></i>Mulai Belanja
                </a>
                
                <a href="{{ route('home') }}#products"
                   class="inline-block rounded-full border border-slate-300 bg-white px-8 py-3 font-semibold text-slate-700 hover:border-slate-400">
                    <i class="fas fa-fire mr-2"></i>Lihat Produk Unggulan
                </a>
            </div>
            
            <div class="mt-16">
                <h3 class="text-2xl font-bold text-slate-900 mb-8">Rekomendasi Untuk Anda</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach(\App\Models\Product::inRandomOrder()->take(4)->get() as $product)
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
                                <h4 class="font-semibold text-lg mb-2 truncate">
                                    <a href="{{ route('product.show', $product) }}" class="hover:text-emerald-700">
                                        {{ $product->name }}
                                    </a>
                                </h4>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-emerald-700">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    @if($product->stock > 0)
                                        <form action="{{ route('cart.add', $product) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit"
                                                    class="rounded-full bg-emerald-600 px-3 py-2 text-white shadow-sm hover:bg-emerald-700">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function incrementQuantity(itemId) {
    const input = document.getElementById(`quantity-${itemId}`);
    const max = parseInt(input.max);
    const current = parseInt(input.value);
    
    if (current < max) {
        input.value = current + 1;
        showUpdateButton(itemId);
        calculateTotals();
    } else {
        alert(`Stok maksimal adalah ${max} item`);
    }
}

function decrementQuantity(itemId) {
    const input = document.getElementById(`quantity-${itemId}`);
    const min = parseInt(input.min);
    const current = parseInt(input.value);
    
    if (current > min) {
        input.value = current - 1;
        showUpdateButton(itemId);
        calculateTotals();
    }
}

function showUpdateButton(itemId) {
    const updateBtn = document.getElementById(`update-btn-${itemId}`);
    updateBtn.classList.remove('hidden');
}

function calculateTotals() {
    const subtotalElement = document.getElementById('subtotal');
    const taxElement = document.getElementById('tax');
    const totalElement = document.getElementById('total');
    
    subtotalElement.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghitung...';
    taxElement.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghitung...';
    totalElement.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghitung...';
    
    setTimeout(() => {
        subtotalElement.textContent = 'Rp 0';
        taxElement.textContent = 'Rp 0';
        totalElement.textContent = 'Rp 0';
    }, 1000);
}

document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function() {
        const itemId = this.getAttribute('data-item-id');
        showUpdateButton(itemId);
        calculateTotals();
    });
});
</script>
@endpush
@endsection
