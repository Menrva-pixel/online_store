@extends('layouts.app')

@section('title', 'Checkout - Toko Online')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Progress Steps -->
    <div class="mb-12">
        <div class="flex items-center justify-center">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="ml-4">
                    <span class="block text-sm font-medium text-blue-600">Keranjang</span>
                </div>
            </div>
            
            <div class="flex-1 border-t-2 border-blue-600 mx-4"></div>
            
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white">
                    <span class="font-bold">2</span>
                </div>
                <div class="ml-4">
                    <span class="block text-sm font-medium text-blue-600">Checkout</span>
                </div>
            </div>
            
            <div class="flex-1 border-t-2 border-gray-300 mx-4"></div>
            
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-600">
                    <span class="font-bold">3</span>
                </div>
                <div class="ml-4">
                    <span class="block text-sm font-medium text-gray-500">Pembayaran</span>
                </div>
            </div>
        </div>
    </div>

    <h1 class="text-3xl font-bold text-gray-800 mb-8">
        <i class="fas fa-cash-register mr-3"></i>Checkout
    </h1>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Detail order- kolom kiri -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informasi Pengiriman -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b">
                        <i class="fas fa-truck mr-2"></i>Informasi Pengiriman
                    </h2>
                    
                    <div class="space-y-6">
                        <!-- Nama Penerima -->
                        <div>
                            <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2"></i>Nama Penerima
                            </label>
                            <input type="text" 
                                   id="recipient_name" 
                                   name="recipient_name"
                                   value="{{ old('recipient_name', auth()->user()->name) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Masukkan nama penerima">
                            @error('recipient_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telpon Penerima -->
                        <div>
                            <label for="recipient_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone mr-2"></i>Nomor Telepon
                            </label>
                            <input type="text" 
                                   id="recipient_phone" 
                                   name="recipient_phone"
                                   value="{{ old('recipient_phone') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Masukkan nomor telepon">
                            @error('recipient_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Pengiriman -->
                        <div>
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt mr-2"></i>Alamat Pengiriman
                            </label>
                            <textarea id="shipping_address" 
                                      name="shipping_address"
                                      rows="4"
                                      required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Masukkan alamat lengkap pengiriman">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-sticky-note mr-2"></i>Catatan (Opsional)
                            </label>
                            <textarea id="notes" 
                                      name="notes"
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Catatan untuk penjual">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b">
                        <i class="fas fa-boxes mr-2"></i>Detail Pesanan
                    </h2>
                    
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                <div class="w-20 h-20 flex-shrink-0">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}"
                                             class="w-full h-full object-cover rounded">
                                    @else
                                        <div class="w-full h-full bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="ml-4 flex-1">
                                    <h4 class="font-semibold text-gray-800">{{ $item->product->name }}</h4>
                                    <div class="flex justify-between mt-2">
                                        <div>
                                            <span class="text-gray-600">{{ $item->quantity }} x </span>
                                            <span class="font-medium text-blue-600">
                                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="font-bold text-gray-800">
                                            Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pesanan - Kolom Kanan -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b">
                        <i class="fas fa-receipt mr-2"></i>Ringkasan Pesanan
                    </h2>

                    <!-- Ringkasan Pesanan -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Biaya Pengiriman</span>
                            <span class="font-medium">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pajak (10%)</span>
                            <span class="font-medium">
                                Rp {{ number_format($tax, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-800">Total</span>
                            <span class="text-2xl font-bold text-blue-600">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Payment Terms -->
                    <div class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <h4 class="font-bold text-yellow-800 mb-2">
                            <i class="fas fa-clock mr-2"></i>Batas Waktu Pembayaran
                        </h4>
                        <p class="text-sm text-yellow-700">
                            Anda memiliki waktu <span class="font-bold">24 jam</span> untuk melakukan pembayaran.
                            Pesanan akan otomatis dibatalkan jika tidak dibayar dalam waktu tersebut.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition duration-300 text-lg font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>Konfirmasi Pesanan
                    </button>

                    <!-- Back to Cart -->
                    <div class="mt-4 text-center">
                        <a href="{{ route('cart.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali ke Keranjang
                        </a>
                    </div>

                    <!-- Security Info -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-shield-alt text-blue-600 mr-2"></i>
                            <span class="font-medium text-blue-800">Pembayaran Aman</span>
                        </div>
                        <p class="text-sm text-blue-700">
                            Data Anda dilindungi dengan enkripsi SSL. Pembayaran akan diverifikasi oleh CS.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection