@extends('layouts.app')

@section('title', 'Detail Pesanan - Toko Online')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-receipt mr-3"></i>Detail Pesanan
                </h1>
                <div class="flex items-center space-x-4">
                    <div>
                        <span class="text-gray-600">Order ID:</span>
                        <span class="font-bold text-blue-600 ml-2">{{ $order->order_number }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Tanggal:</span>
                        <span class="font-medium ml-2">{{ $order->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 md:mt-0">
                @php
                    $status = $order->statusLabel;
                @endphp
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ $status['color'] }}">
                    {{ $status['text'] }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b">
                    <i class="fas fa-boxes mr-2"></i>Item Pesanan
                </h2>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
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
                                    <div class="text-gray-600">
                                        {{ $item->quantity }} x {{ $item->formatted_price }}
                                    </div>
                                    <span class="font-bold text-gray-800">
                                        {{ $item->formatted_subtotal }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b">
                    <i class="fas fa-truck mr-2"></i>Informasi Pengiriman
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">Penerima</h4>
                        <div class="text-gray-800">
                            <div class="font-bold">{{ $order->recipient_name }}</div>
                            <div>{{ $order->recipient_phone }}</div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">Alamat</h4>
                        <div class="text-gray-800">
                            {{ $order->shipping_address }}
                        </div>
                    </div>
                    
                    @if($order->notes)
                    <div class="md:col-span-2">
                        <h4 class="font-medium text-gray-700 mb-2">Catatan</h4>
                        <div class="text-gray-800 bg-gray-50 p-4 rounded">
                            {{ $order->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            @if($order->paymentProof)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b">
                    <i class="fas fa-credit-card mr-2"></i>Informasi Pembayaran
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">Status Pembayaran</h4>
                        @php
                            $paymentStatus = $order->paymentProof->statusLabel;
                        @endphp
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $paymentStatus['color'] }}">
                            {{ $paymentStatus['text'] }}
                        </span>
                        
                        @if($order->paymentProof->verified_at)
                            <div class="mt-2 text-sm text-gray-600">
                                Diverifikasi: {{ $order->paymentProof->verified_at->format('d M Y H:i') }}
                            </div>
                        @endif
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">Metode Pembayaran</h4>
                        <div class="text-gray-800">
                            @if($order->paymentProof->payment_method === 'bank_transfer')
                                <div>Transfer Bank</div>
                                @if($order->paymentProof->bank_name)
                                    <div class="text-sm text-gray-600">
                                        Bank: {{ $order->paymentProof->bank_name }}
                                    </div>
                                @endif
                            @elseif($order->paymentProof->payment_method === 'e_wallet')
                                <div>E-Wallet</div>
                            @else
                                <div>COD (Cash on Delivery)</div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Payment Proof Image -->
                    @if($order->paymentProof->proof_image)
                    <div class="md:col-span-2">
                        <h4 class="font-medium text-gray-700 mb-2">Bukti Pembayaran</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <img src="{{ $order->paymentProof->proof_image_url }}" 
                                 alt="Bukti Pembayaran"
                                 class="max-w-xs rounded-lg shadow cursor-pointer"
                                 onclick="openImageModal('{{ $order->paymentProof->proof_image_url }}')">
                        </div>
                    </div>
                    @endif
                    
                    @if($order->paymentProof->notes)
                    <div class="md:col-span-2">
                        <h4 class="font-medium text-gray-700 mb-2">Catatan Pembayaran</h4>
                        <div class="text-gray-800 bg-gray-50 p-4 rounded">
                            {{ $order->paymentProof->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Order Actions -->
            @if($order->canBeCancelled())
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b">
                    <i class="fas fa-cog mr-2"></i>Aksi Pesanan
                </h2>
                
                <div class="flex space-x-4">
                    @if($order->status === 'waiting_payment' && !$order->hasPaymentProof())
                        <a href="{{ route('payment.show', $order) }}" 
                           class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-credit-card mr-2"></i>Upload Bukti Bayar
                        </a>
                    @endif
                    
                    @if($order->canBeCancelled())
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700">
                                <i class="fas fa-times mr-2"></i>Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b">
                    <i class="fas fa-receipt mr-2"></i>Ringkasan Pesanan
                </h2>

                <!-- Order Summary -->
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">
                            {{ $order->formatted_total }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Biaya Pengiriman</span>
                        <span class="font-medium">{{ $order->formatted_shipping }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pajak (10%)</span>
                        <span class="font-medium">
                            {{ $order->formatted_tax }}
                        </span>
                    </div>
                </div>

                <!-- Total -->
                <div class="border-t border-gray-200 pt-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-800">Total</span>
                        <span class="text-2xl font-bold text-green-600">
                            {{ $order->formatted_total }}
                        </span>
                    </div>
                </div>

                <!-- Payment Due (if applicable) -->
                @if($order->payment_due_at && $order->status === 'waiting_payment')
                <div class="mb-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <h4 class="font-bold text-yellow-800 mb-2">
                        <i class="fas fa-clock mr-2"></i>Batas Waktu Pembayaran
                    </h4>
                    <p class="text-sm text-yellow-700">
                        {{ $order->payment_due_at->format('d M Y H:i') }}
                    </p>
                </div>
                @endif

                <!-- Back to Orders -->
                <div class="mt-6">
                    <a href="{{ route('orders.index') }}" 
                       class="block text-center bg-gray-200 text-gray-700 py-3 rounded-lg hover:bg-gray-300">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Pesanan
                    </a>
                </div>

                <!-- Support -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-bold text-blue-800 mb-2">
                        <i class="fas fa-headset mr-2"></i>Butuh Bantuan?
                    </h4>
                    <p class="text-sm text-blue-700 mb-2">
                        Hubungi Customer Service:
                    </p>
                    <div class="text-sm">
                        <div class="flex items-center mb-1">
                            <i class="fas fa-phone text-blue-600 mr-2 text-xs"></i>
                            <span>+62 812 3456 7890</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-blue-600 mr-2 text-xs"></i>
                            <span>support@tokoonline.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative">
            <button onclick="closeImageModal()" 
                    class="absolute -top-4 -right-4 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100">
                <i class="fas fa-times text-gray-700"></i>
            </button>
            <img id="modalImage" class="max-w-full max-h-screen rounded-lg">
        </div>
    </div>
</div>

@push('scripts')
<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>
@endpush
@endsection