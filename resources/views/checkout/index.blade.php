@extends('layouts.app')

@section('title', 'Checkout - Toko Online')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Progress Steps -->
    <div class="mb-10">
        <div class="flex items-center justify-center">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-white">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="ml-3">
                    <span class="block text-sm font-medium text-emerald-700">Keranjang</span>
                </div>
            </div>
            
            <div class="flex-1 border-t-2 border-emerald-200 mx-4"></div>
            
            <div class="flex items-center">
                <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center text-white">
                    <span class="font-bold">2</span>
                </div>
                <div class="ml-3">
                    <span class="block text-sm font-medium text-emerald-700">Checkout</span>
                </div>
            </div>
            
            <div class="flex-1 border-t-2 border-slate-200 mx-4"></div>
            
            <div class="flex items-center">
                <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center text-slate-600">
                    <span class="font-bold">3</span>
                </div>
                <div class="ml-3">
                    <span class="block text-sm font-medium text-slate-500">Pembayaran</span>
                </div>
            </div>
        </div>
    </div>

    <h1 class="text-3xl font-bold text-slate-900 mb-8">
        <i class="fas fa-cash-register mr-3"></i>Checkout
    </h1>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Detail order- kolom kiri -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informasi Pengiriman -->
                <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100">
                        <i class="fas fa-truck mr-2"></i>Informasi Pengiriman
                    </h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="recipient_name" class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fas fa-user mr-2"></i>Nama Penerima
                            </label>
                            <input type="text"
                                   id="recipient_name"
                                   name="recipient_name"
                                   value="{{ old('recipient_name', auth()->user()->name) }}"
                                   required
                                   class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                   placeholder="Masukkan nama penerima">
                            @error('recipient_name')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="recipient_phone" class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fas fa-phone mr-2"></i>Nomor Telepon
                            </label>
                            <input type="text"
                                   id="recipient_phone"
                                   name="recipient_phone"
                                   value="{{ old('recipient_phone') }}"
                                   required
                                   class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                   placeholder="Masukkan nomor telepon">
                            @error('recipient_phone')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="shipping_address" class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fas fa-map-marker-alt mr-2"></i>Alamat Pengiriman
                            </label>
                            <textarea id="shipping_address"
                                      name="shipping_address"
                                      rows="4"
                                      required
                                      class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                      placeholder="Masukkan alamat lengkap pengiriman">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fas fa-sticky-note mr-2"></i>Catatan (Opsional)
                            </label>
                            <textarea id="notes"
                                      name="notes"
                                      rows="3"
                                      class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                      placeholder="Catatan untuk penjual">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100">
                        <i class="fas fa-credit-card mr-2"></i>Metode Pembayaran
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="border border-slate-200 rounded-2xl p-4 hover:border-emerald-300 transition">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio"
                                       name="payment_method"
                                       value="bank_transfer"
                                       {{ old('payment_method', 'bank_transfer') == 'bank_transfer' ? 'checked' : '' }}
                                       required
                                       class="h-5 w-5 text-emerald-600 focus:ring-emerald-500">
                                <div class="ml-3">
                                    <span class="font-medium text-slate-700">Bank Transfer</span>
                                    <p class="text-sm text-slate-500 mt-1">
                                        Transfer ke rekening bank kami. Instruksi akan dikirim via email.
                                    </p>
                                </div>
                            </label>
                        </div>

                        <div class="border border-slate-200 rounded-2xl p-4 hover:border-emerald-300 transition">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio"
                                       name="payment_method"
                                       value="ewallet"
                                       {{ old('payment_method') == 'ewallet' ? 'checked' : '' }}
                                       class="h-5 w-5 text-emerald-600 focus:ring-emerald-500">
                                <div class="ml-3">
                                    <span class="font-medium text-slate-700">E-Wallet</span>
                                    <p class="text-sm text-slate-500 mt-1">
                                        Dana, OVO, GoPay, atau LinkAja
                                    </p>
                                </div>
                            </label>
                        </div>

                        <div class="border border-slate-200 rounded-2xl p-4 hover:border-emerald-300 transition">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio"
                                       name="payment_method"
                                       value="cod"
                                       {{ old('payment_method') == 'cod' ? 'checked' : '' }}
                                       class="h-5 w-5 text-emerald-600 focus:ring-emerald-500">
                                <div class="ml-3">
                                    <span class="font-medium text-slate-700">Cash On Delivery (COD)</span>
                                    <p class="text-sm text-slate-500 mt-1">
                                        Bayar saat barang sampai. Tambahan biaya Rp 5.000.
                                    </p>
                                </div>
                            </label>
                        </div>

                        @error('payment_method')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Order Items -->
                <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100">
                        <i class="fas fa-boxes mr-2"></i>Detail Pesanan
                    </h2>
                    
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            <div class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="w-20 h-20 flex-shrink-0">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-full h-full object-cover rounded-xl">
                                    @else
                                        <div class="w-full h-full bg-slate-100 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-box text-slate-300"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="ml-4 flex-1">
                                    <h4 class="font-semibold text-slate-800">{{ $item->product->name }}</h4>
                                    <div class="flex justify-between mt-2 text-sm">
                                        <div>
                                            <span class="text-slate-600">{{ $item->quantity }} x </span>
                                            <span class="font-medium text-emerald-700">
                                                Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="font-bold text-slate-900">
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
                <div class="rounded-3xl border border-slate-200 bg-white/80 p-6 shadow-sm sticky top-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100">
                        <i class="fas fa-receipt mr-2"></i>Ringkasan Pesanan
                    </h2>

                    <div class="space-y-4 mb-6 text-sm text-slate-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-semibold text-slate-900">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Pengiriman</span>
                            <span class="font-semibold text-slate-900">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pajak (10%)</span>
                            <span class="font-semibold text-slate-900">
                                Rp {{ number_format($tax, 0, ',', '.') }}
                            </span>
                        </div>

                        <div id="codFee" class="flex justify-between text-rose-600 hidden">
                            <span>Biaya COD</span>
                            <span class="font-semibold">Rp 5.000</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-slate-900">Total</span>
                            <span class="text-2xl font-bold text-emerald-700" id="totalAmount">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-6 rounded-2xl border border-amber-100 bg-amber-50 p-4 text-sm text-amber-700">
                        <h4 class="font-bold mb-2">
                            <i class="fas fa-clock mr-2"></i>Batas Waktu Pembayaran
                        </h4>
                        Anda memiliki waktu <span class="font-bold">24 jam</span> untuk melakukan pembayaran.
                        Pesanan akan otomatis dibatalkan jika tidak dibayar dalam waktu tersebut.
                    </div>

                    <button type="submit"
                            class="w-full rounded-full bg-emerald-600 text-white py-3 px-4 text-lg font-semibold hover:bg-emerald-700">
                        <i class="fas fa-check-circle mr-2"></i>Konfirmasi Pesanan
                    </button>

                    <div class="mt-4 text-center">
                        <a href="{{ route('cart.index') }}" class="text-emerald-700 hover:text-emerald-800 text-sm">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali ke Keranjang
                        </a>
                    </div>

                    <div class="mt-6 rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-sm text-emerald-700">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-shield-alt text-emerald-600 mr-2"></i>
                            <span class="font-medium">Pembayaran Aman</span>
                        </div>
                        Data Anda dilindungi dengan enkripsi SSL. Pembayaran akan diverifikasi oleh CS.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codRadio = document.querySelector('input[value="cod"]');
    const codFeeElement = document.getElementById('codFee');
    const totalAmountElement = document.getElementById('totalAmount');
    const originalTotal = {{ $total }};
    
    function updateTotal() {
        if (codRadio.checked) {
            codFeeElement.classList.remove('hidden');
            const newTotal = originalTotal + 5000;
            totalAmountElement.textContent = 'Rp ' + formatNumber(newTotal);
        } else {
            codFeeElement.classList.add('hidden');
            totalAmountElement.textContent = 'Rp ' + formatNumber(originalTotal);
        }
    }
    
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', updateTotal);
    });
    
    updateTotal();
});
</script>
@endsection
