@extends('layouts.app')

@section('title', 'Keranjang Belanja - Toko Online')

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
                    <span class="text-gray-700 font-medium">Keranjang Belanja</span>
                </div>
            </li>
        </ol>
    </nav>

    <h1 class="text-3xl font-bold text-gray-800 mb-8">
        <i class="fas fa-shopping-cart mr-3"></i>Keranjang Belanja
    </h1>

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <!-- Cart Header -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-semibold text-gray-700">
                                {{ $cartItems->count() }} item di keranjang
                            </span>
                        </div>
                        <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-plus mr-1"></i>Tambah Produk Lain
                        </a>
                    </div>
                </div>

                <!-- Cart Items List -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @foreach($cartItems as $item)
                        <div class="border-b border-gray-200 p-6 hover:bg-gray-50 transition duration-200">
                            <div class="flex flex-col sm:flex-row">
                                <!-- Product Image -->
                                <div class="sm:w-32 sm:h-32 w-full h-48 mb-4 sm:mb-0">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}"
                                             class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400 text-4xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="sm:ml-6 flex-1">
                                    <div class="flex justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                                <a href="{{ route('product.show', $item->product) }}" 
                                                   class="hover:text-blue-600">
                                                    {{ $item->product->name }}
                                                </a>
                                            </h3>
                                            
                                            <!-- Stock Status -->
                                            @if($item->product->stock <= 0)
                                                <div class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm mb-2">
                                                    <i class="fas fa-exclamation-circle mr-1"></i>Habis
                                                </div>
                                            @elseif($item->product->stock < $item->quantity)
                                                <div class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm mb-2">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Stok hanya {{ $item->product->stock }} tersedia
                                                </div>
                                            @endif
                                            
                                            <p class="text-gray-600 text-sm mb-4">
                                                {{ Str::limit($item->product->description, 150) }}
                                            </p>
                                        </div>

                                        <!-- Price -->
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-blue-600 mb-2">
                                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                per item
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quantity Controls & Actions -->
                                    <div class="flex justify-between items-center mt-4">
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center">
                                            <span class="text-gray-700 mr-4">Jumlah:</span>
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                                                @csrf
                                                @method('PUT')
                                                <button type="button" 
                                                        onclick="decrementQuantity({{ $item->id }})" 
                                                        class="bg-gray-200 text-gray-700 px-3 py-2 rounded-l-lg hover:bg-gray-300">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" 
                                                       id="quantity-{{ $item->id }}" 
                                                       name="quantity" 
                                                       value="{{ $item->quantity }}" 
                                                       min="1" 
                                                       max="{{ $item->product->stock }}"
                                                       class="w-20 text-center border-y border-gray-300 py-2 quantity-input"
                                                       data-item-id="{{ $item->id }}">
                                                <button type="button" 
                                                        onclick="incrementQuantity({{ $item->id }})" 
                                                        class="bg-gray-200 text-gray-700 px-3 py-2 rounded-r-lg hover:bg-gray-300">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <button type="submit" 
                                                        class="ml-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 hidden update-btn"
                                                        id="update-btn-{{ $item->id }}">
                                                    <i class="fas fa-sync-alt mr-2"></i>Update
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Item Total & Remove -->
                                        <div class="text-right">
                                            <div class="text-xl font-bold text-gray-800 mb-2">
                                                Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                            </div>
                                            <form action="{{ route('cart.remove', $item) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 text-sm"
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

                <!-- Empty Cart Message (Hidden) -->
                <div id="empty-cart-message" class="hidden text-center py-12 bg-white rounded-lg shadow">
                    <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">Keranjang Kosong</h3>
                    <p class="text-gray-500 mb-6">Tambahkan produk ke keranjang untuk mulai berbelanja</p>
                    <a href="{{ route('home') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-shopping-bag mr-2"></i>Mulai Belanja
                    </a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-4">
                        <i class="fas fa-receipt mr-2"></i>Ringkasan Pesanan
                    </h2>

                    <!-- Summary Details -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" id="subtotal">
                                Rp {{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }), 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Biaya Pengiriman</span>
                            <span class="font-medium">Rp 15.000</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pajak (10%)</span>
                            <span class="font-medium" id="tax">
                                Rp {{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }) * 0.1, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-800">Total</span>
                            <span class="text-2xl font-bold text-blue-600" id="total">
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

                    <!-- Checkout Button -->
                    <form action="{{ route('checkout') }}" method="GET">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition duration-300 text-lg font-semibold">
                            <i class="fas fa-credit-card mr-2"></i>Lanjut ke Pembayaran
                        </button>
                    </form>

                    <!-- Security Info -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-shield-alt text-blue-600 mr-2"></i>
                            <span class="font-medium text-blue-800">Belanja Aman</span>
                        </div>
                        <p class="text-sm text-blue-700">
                            Transaksi Anda dilindungi dengan sistem keamanan terbaik.
                        </p>
                    </div>

                    <!-- Payment Methods -->
                    <div class="mt-6">
                        <h4 class="font-medium text-gray-700 mb-3">
                            <i class="fas fa-credit-card mr-2"></i>Metode Pembayaran:
                        </h4>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="bg-gray-100 p-2 rounded text-center">
                                <i class="fas fa-university text-gray-600"></i>
                                <span class="text-xs block mt-1">Transfer Bank</span>
                            </div>
                            <div class="bg-gray-100 p-2 rounded text-center">
                                <i class="fas fa-mobile-alt text-gray-600"></i>
                                <span class="text-xs block mt-1">E-Wallet</span>
                            </div>
                            <div class="bg-gray-100 p-2 rounded text-center">
                                <i class="fas fa-store text-gray-600"></i>
                                <span class="text-xs block mt-1">COD</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Promo Code -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h3 class="font-bold text-gray-800 mb-4">
                        <i class="fas fa-tag mr-2"></i>Kode Promo
                    </h3>
                    <div class="flex">
                        <input type="text" 
                               placeholder="Masukkan kode promo"
                               class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700">
                            Gunakan
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        *Kode promo akan diverifikasi saat checkout
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart State -->
        <div class="text-center py-16">
            <div class="mb-8">
                <div class="inline-block p-8 bg-gray-100 rounded-full">
                    <i class="fas fa-shopping-cart text-gray-400 text-8xl"></i>
                </div>
            </div>
            
            <h2 class="text-3xl font-bold text-gray-700 mb-4">Keranjang Belanjamu Kosong</h2>
            <p class="text-gray-500 text-lg mb-8 max-w-md mx-auto">
                Tambahkan beberapa produk ke keranjang dan kembali ke sini untuk melihat ringkasan pesanan.
            </p>
            
            <div class="space-x-4">
                <a href="{{ route('home') }}" 
                   class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition duration-300 text-lg font-semibold">
                    <i class="fas fa-shopping-bag mr-2"></i>Mulai Belanja
                </a>
                
                <a href="{{ route('home') }}#products" 
                   class="inline-block bg-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-300 transition duration-300 text-lg font-semibold">
                    <i class="fas fa-fire mr-2"></i>Lihat Produk Unggulan
                </a>
            </div>
            
            <!-- Recommended Products -->
            <div class="mt-16">
                <h3 class="text-2xl font-bold text-gray-800 mb-8">Rekomendasi Untuk Anda</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach(\App\Models\Product::inRandomOrder()->take(4)->get() as $product)
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
                                <h4 class="font-bold text-lg mb-2 truncate">
                                    <a href="{{ route('product.show', $product) }}" class="hover:text-blue-600">
                                        {{ $product->name }}
                                    </a>
                                </h4>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-blue-600">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    @if($product->stock > 0)
                                        <form action="{{ route('cart.add', $product) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" 
                                                    class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition duration-300">
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
// Kuantitas increment/decrement functions
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

// Tampilkan tombol update
function showUpdateButton(itemId) {
    const updateBtn = document.getElementById(`update-btn-${itemId}`);
    updateBtn.classList.remove('hidden');
}

// Hitung total harga
function calculateTotals() {
    const subtotalElement = document.getElementById('subtotal');
    const taxElement = document.getElementById('tax');
    const totalElement = document.getElementById('total');
    
    subtotalElement.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghitung...';
    taxElement.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghitung...';
    totalElement.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menghitung...';
    
    // Simulasi calculation delay
    setTimeout(() => {
        subtotalElement.textContent = 'Rp 0';
        taxElement.textContent = 'Rp 0';
        totalElement.textContent = 'Rp 0';
    }, 1000);
}

// Listen to quantity input changes
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