@extends('layouts.app')

@section('title', 'Detail Pesanan - Admin')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-file-invoice mr-3"></i>Detail Pesanan
                </h1>
                <p class="text-gray-600 mt-2">No. Pesanan: <strong>{{ $order->order_number }}</strong></p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.orders') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Order Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Status Pesanan</h2>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'waiting_payment' => 'bg-blue-100 text-blue-800',
                                'processing' => 'bg-purple-100 text-purple-800',
                                'shipped' => 'bg-indigo-100 text-indigo-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            $statusLabels = [
                                'pending' => 'Pending',
                                'waiting_payment' => 'Menunggu Pembayaran',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan'
                            ];
                        @endphp
                        <span class="px-3 py-1 text-sm rounded-full {{ $statusColors[$order->status] }}">
                            {{ $statusLabels[$order->status] }}
                        </span>
                    </div>
                    
                    <!-- Status Update Form -->
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="mt-4 md:mt-0">
                        @csrf
                        <div class="flex items-center space-x-2">
                            <select name="status" 
                                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="waiting_payment" {{ $order->status == 'waiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800">Item Pesanan</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <div class="px-6 py-4 flex items-center">
                            <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}"
                                         class="h-16 w-16 object-cover rounded-lg">
                                @else
                                    <i class="fas fa-box text-gray-400"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">{{ $item->product->name }}</div>
                                <div class="text-sm text-gray-600">Jumlah: {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            </div>
                            <div class="text-lg font-bold text-gray-900">
                                Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div class="text-gray-700">Subtotal</div>
                        <div class="font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <div class="text-gray-700">Ongkos Kirim</div>
                        <div class="font-medium">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-300">
                        <div class="text-xl font-bold text-gray-900">Total</div>
                        <div class="text-2xl font-bold text-gray-900">
                            Rp {{ number_format($order->total + ($order->shipping_cost ?? 0), 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pengiriman</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600">Nama Penerima</div>
                        <div class="font-medium text-gray-900">{{ $order->recipient_name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Telepon</div>
                        <div class="font-medium text-gray-900">{{ $order->recipient_phone ?? '-' }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-sm text-gray-600">Alamat</div>
                        <div class="font-medium text-gray-900">{{ $order->shipping_address }}</div>
                    </div>
                    @if($order->shipping_courier)
                        <div>
                            <div class="text-sm text-gray-600">Kurir</div>
                            <div class="font-medium text-gray-900">{{ $order->shipping_courier }}</div>
                        </div>
                    @endif
                    @if($order->tracking_number)
                        <div>
                            <div class="text-sm text-gray-600">No. Resi</div>
                            <div class="font-medium text-gray-900">{{ $order->tracking_number }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Customer & Payment -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Customer</h2>
                <div class="space-y-3">
                    <div>
                        <div class="text-sm text-gray-600">Nama</div>
                        <div class="font-medium text-gray-900">{{ $order->user->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Email</div>
                        <div class="font-medium text-gray-900">{{ $order->user->email }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Bergabung</div>
                        <div class="font-medium text-gray-900">{{ $order->user->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pembayaran</h2>
                <div class="space-y-4">
                    <div>
                        <div class="text-sm text-gray-600">Metode Pembayaran</div>
                        <div class="font-medium text-gray-900">{{ $order->payment_method ?? 'Transfer Bank' }}</div>
                    </div>
                    
                    @if($order->paymentProof)
                        <div>
                            <div class="text-sm text-gray-600 mb-2">Bukti Pembayaran</div>
                            @if(strpos($order->paymentProof->proof_image, '.pdf') !== false)
                                <a href="{{ asset('storage/' . $order->paymentProof->proof_image) }}" 
                                   target="_blank"
                                   class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-file-pdf mr-2"></i>Download PDF
                                </a>
                            @else
                                <img src="{{ asset('storage/' . $order->paymentProof->proof_image) }}" 
                                     alt="Bukti Pembayaran"
                                     class="w-full h-auto rounded-lg shadow">
                            @endif
                            <div class="mt-2 text-sm text-gray-600">
                                Diupload: {{ $order->paymentProof->created_at->format('d M Y H:i') }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-file-invoice-dollar text-3xl mb-2"></i>
                            <p>Belum ada bukti pembayaran</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Timeline Pesanan</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="bg-green-100 p-2 rounded-full mr-4">
                            <i class="fas fa-shopping-cart text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Pesanan Dibuat</div>
                            <div class="text-sm text-gray-600">{{ $order->created_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                    @if($order->updated_at != $order->created_at)
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-2 rounded-full mr-4">
                                <i class="fas fa-sync-alt text-blue-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Terakhir Diupdate</div>
                                <div class="text-sm text-gray-600">{{ $order->updated_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-8 flex justify-between">
        <a href="{{ route('admin.orders') }}" 
           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
        </a>
        <div class="space-x-4">
            <button onclick="window.print()" 
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                <i class="fas fa-print mr-2"></i>Cetak Invoice
            </button>
            @if(in_array($order->status, ['pending', 'waiting_payment']))
                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            name="status" 
                            value="cancelled"
                            onclick="return confirm('Batalkan pesanan {{ $order->order_number }}?')"
                            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center">
                        <i class="fas fa-times mr-2"></i>Batalkan Pesanan
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection