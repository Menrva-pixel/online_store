@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <nav class="flex mb-4">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a>
            <span class="mx-2 text-gray-400">/</span>
            <a href="{{ route('my.orders.index') }}" class="text-gray-500 hover:text-gray-700">Pesanan Saya</a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-700 font-medium">Detail Pesanan</span>
        </nav>
        
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Detail Pesanan</h1>
                <p class="text-gray-600">No. Order: <span class="font-semibold">{{ $order->order_number }}</span></p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->status_label['color'] }}">
                {{ $order->status_label['text'] }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Items Pesanan</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($order->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @php
                                            $productImage = optional($item->product->images)->first();
                                        @endphp
                                        
                                        @if($productImage && $productImage->image_path)
                                        <div class="h-16 w-16 flex-shrink-0">
                                            <img src="{{ asset('storage/' . $productImage->image_path) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="h-full w-full object-cover rounded-lg">
                                        </div>
                                        @else
                                        <div class="h-16 w-16 flex-shrink-0 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        @endif
                                        <div class="ml-4">
                                            <h3 class="text-sm font-medium text-gray-900">{{ $item->product->name ?? 'Produk tidak tersedia' }}</h3>
                                            <p class="text-sm text-gray-500">SKU: {{ $item->product->sku ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <p class="text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <p class="text-sm text-gray-900">{{ $item->quantity }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payment Proof Upload -->
            @if($order->payment_method != 'cod' && $order->status == 'waiting_payment' && !$order->paymentProof)
            <div class="bg-white rounded-xl shadow-sm border border-blue-200 overflow-hidden">
                <div class="px-6 py-4 bg-blue-50 border-b border-blue-200">
                    <h2 class="text-lg font-semibold text-blue-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Upload Bukti Pembayaran
                    </h2>
                </div>
                <div class="p-6">
                    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <svg class="h-5 w-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <p class="text-sm text-yellow-800">Silakan upload bukti pembayaran untuk melanjutkan proses pesanan.</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('my.orders.payment.upload', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">File Bukti Transfer</label>
                            <div class="flex">
                                <input type="file" 
                                       id="proof_image" 
                                       name="proof_image" 
                                       accept="image/*,.pdf"
                                       required
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <button type="button" 
                                        onclick="previewFile()"
                                        class="px-4 py-2 border border-l-0 border-gray-300 rounded-r-lg bg-gray-50 hover:bg-gray-100">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF, PDF. Maksimal 2MB</p>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                            <textarea id="notes" 
                                      name="notes" 
                                      rows="3"
                                      placeholder="Contoh: Transfer dari bank BCA, nama pengirim: ..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Upload & Kirim untuk Verifikasi
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Existing Payment Proof -->
            @if($order->paymentProof)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Bukti Pembayaran
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                @if(str_ends_with($order->paymentProof->proof_image, '.pdf'))
                                <div class="text-center">
                                    <svg class="h-16 w-16 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">File PDF</p>
                                </div>
                                @else
                                <img src="{{ asset('storage/' . $order->paymentProof->proof_image) }}" 
                                     alt="Bukti Pembayaran" 
                                     class="w-full h-auto rounded-lg">
                                @endif
                            </div>
                        </div>
                        
                        <div class="md:col-span-2 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status Verifikasi</label>
                                @if($order->paymentProof->status == 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Menunggu Verifikasi
                                </span>
                                @elseif($order->paymentProof->status == 'verified')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Terverifikasi
                                </span>
                                @elseif($order->paymentProof->status == 'rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Ditolak
                                </span>
                                @endif
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Metode Pembayaran</label>
                                <p class="text-gray-900 font-medium">{{ ucfirst(str_replace('_', ' ', $order->paymentProof->payment_method)) }}</p>
                            </div>
                            
                            @if($order->paymentProof->bank_name)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Bank Pengirim</label>
                                <p class="text-gray-900 font-medium">{{ $order->paymentProof->bank_name }}</p>
                            </div>
                            @endif
                            
                            @if($order->paymentProof->notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Catatan</label>
                                <p class="text-gray-900">{{ $order->paymentProof->notes }}</p>
                            </div>
                            @endif
                            
                            @if($order->paymentProof->verified_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Waktu Verifikasi</label>
                                <p class="text-gray-900">{{ $order->paymentProof->verified_at->format('d M Y, H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Shipping Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Informasi Pengiriman
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nama Penerima</label>
                            <p class="text-gray-900 font-medium">{{ $order->recipient_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Telepon</label>
                            <p class="text-gray-900 font-medium">{{ $order->recipient_phone }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Pengiriman</label>
                        <p class="text-gray-900 font-medium">{{ $order->shipping_address }}</p>
                    </div>
                    
                    @if($order->notes)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Catatan Pesanan</label>
                        <p class="text-gray-900">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Order Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Ringkasan Pesanan</h2>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">Rp {{ number_format($order->total - ($order->shipping_cost ?? 0), 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Biaya Pengiriman</span>
                        <span class="font-medium">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($order->payment_method == 'cod')
                    <div class="flex justify-between">
                        <span class="text-gray-600">Biaya COD</span>
                        <span class="font-medium">Rp 5.000</span>
                    </div>
                    @endif
                    
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between text-lg font-semibold">
                            <span>Total</span>
                            <span class="text-blue-600">{{ $order->formatted_total_with_shipping ?? 'Rp ' . number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Metode Pembayaran</label>
                        <div class="flex items-center">
                            @if($order->payment_method == 'bank_transfer')
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <span class="font-medium">Transfer Bank</span>
                            @elseif($order->payment_method == 'cod')
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="font-medium">Cash on Delivery</span>
                            @elseif($order->payment_method == 'ewallet')
                            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="font-medium">E-Wallet</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 space-y-3">
                    <a href="{{ route('my.orders.index') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar Pesanan
                    </a>
                    
                    <a href="{{ route('home') }}" 
                       class="w-full px-4 py-3 border border-blue-600 text-blue-600 font-medium rounded-lg hover:bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Lanjut Belanja
                    </a>
                    
                    @if(in_array($order->status, ['waiting_payment', 'pending']))
                    <button onclick="openCancelModal()"
                            class="w-full px-4 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Batalkan Pesanan
                    </button>
                    @endif
                    
                    @if($order->tracking_number)
                    <button onclick="trackOrder('{{ $order->tracking_number }}')"
                            class="w-full px-4 py-3 bg-blue-100 text-blue-700 font-medium rounded-lg hover:bg-blue-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Lacak Pengiriman
                    </button>
                    @endif
                </div>
            </div>

            <!-- Order Timeline -->
            @if($order->processed_at || $order->shipped_at || $order->completed_at)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Timeline Pesanan
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Pesanan Dibuat</h4>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($order->processed_at)
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Sedang Diproses</h4>
                                <p class="text-sm text-gray-500">{{ $order->processed_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->shipped_at)
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Dikirim</h4>
                                <p class="text-sm text-gray-500">{{ $order->shipped_at->format('d M Y, H:i') }}</p>
                                @if($order->tracking_number)
                                <p class="text-sm text-blue-600">No. Resi: {{ $order->tracking_number }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        @if($order->completed_at)
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Selesai</h4>
                                <p class="text-sm text-gray-500">{{ $order->completed_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-4 w-full max-w-md">
        <div class="bg-white rounded-xl shadow-lg">
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Pembatalan</h3>
                    <button onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="mt-4">
                    <p class="text-gray-700">Anda yakin ingin membatalkan pesanan ini?</p>
                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <svg class="h-5 w-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <p class="text-sm text-yellow-800">Pesanan yang sudah dibatalkan tidak dapat dikembalikan.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button onclick="closeCancelModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <form action="{{ route('my.orders.cancel', $order) }}" method="POST" id="cancelForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Ya, Batalkan Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function trackOrder(trackingNumber) {
    const courier = '{{ $order->shipping_courier }}'.toLowerCase();
    let trackingUrl = '';
    
    if (courier && courier.includes('jne')) {
        trackingUrl = `https://www.jne.co.id/id/tracking/trace?awb=${trackingNumber}`;
    } else if (courier && courier.includes('tiki')) {
        trackingUrl = `https://tiki.id/id/tracking?q=${trackingNumber}`;
    } else if (courier && courier.includes('pos')) {
        trackingUrl = `https://www.posindonesia.co.id/id/tracking?resi=${trackingNumber}`;
    } else {
        trackingUrl = '#';
        alert('URL tracking tidak tersedia untuk kurir ini');
        return;
    }
    
    window.open(trackingUrl, '_blank');
}

function previewFile() {
    const fileInput = document.getElementById('proof_image');
    if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        const fileUrl = URL.createObjectURL(file);
        window.open(fileUrl, '_blank');
    }
}

function openCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking outside
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target.id === 'cancelModal') {
        closeCancelModal();
    }
});
</script>
@endsection