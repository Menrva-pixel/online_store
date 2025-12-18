@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - CS Layer 1')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-file-invoice-dollar mr-3"></i>Verifikasi Pembayaran
                </h1>
                <p class="text-gray-600 mt-2">Order: <strong>{{ $paymentProof->order->order_number }}</strong></p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('cs1.payments.pending') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <div>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                <div>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Order & Payment Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-shopping-cart mr-2"></i>Informasi Pesanan
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600">No. Order</div>
                        <div class="font-medium text-gray-900">{{ $paymentProof->order->order_number }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Customer</div>
                        <div class="font-medium text-gray-900">{{ $paymentProof->order->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $paymentProof->order->user->email }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Total Pesanan</div>
                        <div class="text-lg font-bold text-gray-900">
                            Rp {{ number_format($paymentProof->order->total, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Status Pesanan</div>
                        @php
                            $statusColors = [
                                'waiting_payment' => 'bg-blue-100 text-blue-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-sm rounded-full {{ $statusColors[$paymentProof->order->status] ?? 'bg-gray-100' }}">
                            Menunggu Pembayaran
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment Proof -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-receipt mr-2"></i>Bukti Pembayaran
                </h2>
                
                <div class="mb-6">
                    <div class="text-sm text-gray-600 mb-2">File Bukti</div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('cs1.payments.view', $paymentProof) }}" 
                           target="_blank"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center">
                            <i class="fas fa-eye mr-2"></i>Lihat Bukti
                        </a>
                        <a href="{{ route('cs1.payments.download', $paymentProof) }}" 
                           class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition flex items-center">
                            <i class="fas fa-download mr-2"></i>Download
                        </a>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600">Tanggal Upload</div>
                        <div class="font-medium text-gray-900">
                            {{ $paymentProof->created_at->format('d M Y H:i:s') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Status Verifikasi</div>
                        <span class="px-2 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>Menunggu Verifikasi
                        </span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Item Pesanan</h2>
                <div class="space-y-4">
                    @foreach($paymentProof->order->items as $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}"
                                         class="h-12 w-12 object-cover rounded mr-4">
                                @else
                                    <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center mr-4">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $item->product->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="font-bold text-gray-900">
                                Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column - Verification Actions -->
        <div class="space-y-6">
            <!-- Verification Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-check-circle mr-2"></i>Verifikasi Pembayaran
                </h2>
                
                <!-- Verify Form -->
                <form action="{{ route('cs1.payments.verify', $paymentProof) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="mb-4">
                        <label for="verify_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="notes" 
                                  id="verify_notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                  placeholder="Catatan verifikasi..."></textarea>
                    </div>
                    <button type="submit" 
                            onclick="return confirm('Verifikasi pembayaran ini?')"
                            class="w-full py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                        <i class="fas fa-check mr-2"></i>Verifikasi Pembayaran
                    </button>
                </form>

                <!-- Reject Form -->
                <form action="{{ route('cs1.payments.reject', $paymentProof) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="reject_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan *
                        </label>
                        <textarea name="reason" 
                                  id="reject_reason" 
                                  rows="3"
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="Alasan menolak pembayaran..."></textarea>
                    </div>
                    <button type="submit" 
                            onclick="return confirm('Tolak pembayaran ini?')"
                            class="w-full py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                        <i class="fas fa-times mr-2"></i>Tolak Pembayaran
                    </button>
                </form>
            </div>

            <!-- Verification Guide -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                <h3 class="text-lg font-bold text-gray-800 mb-3">
                    <i class="fas fa-info-circle mr-2"></i>Periksa dengan Teliti:
                </h3>
                <ul class="text-sm text-gray-700 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-2 mt-0.5"></i>
                        <span>Nama pengirim di bukti transfer harus sama dengan nama customer</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-2 mt-0.5"></i>
                        <span>Jumlah transfer harus sesuai dengan total pesanan</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-2 mt-0.5"></i>
                        <span>Tanggal transfer tidak boleh lebih dari 3 hari dari tanggal order</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-green-500 mr-2 mt-0.5"></i>
                        <span>Pastikan bukti transfer jelas dan tidak terpotong</span>
                    </li>
                </ul>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-history mr-2"></i>Timeline
                </h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="bg-gray-100 p-2 rounded-full mr-3">
                            <i class="fas fa-shopping-cart text-gray-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Pesanan Dibuat</div>
                            <div class="text-sm text-gray-600">{{ $paymentProof->order->created_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <i class="fas fa-upload text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">Bukti Diupload</div>
                            <div class="text-sm text-gray-600">{{ $paymentProof->created_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection