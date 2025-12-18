@extends('layouts.app')

@section('title', 'CS Layer 1 Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-headset mr-3"></i>CS Layer 1 Dashboard
        </h1>
        <p class="text-gray-600 mt-2">Selamat datang, {{ auth()->user()->name }}! Kelola verifikasi pembayaran.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Pending Payments -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Menunggu Verifikasi</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $pendingCount }}</div>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('cs1.payments.pending') }}" 
                   class="text-yellow-600 hover:text-yellow-800 text-sm flex items-center">
                    Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Verified Payments -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Terverifikasi</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $verifiedCount }}</div>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('cs1.payments.verified') }}" 
                   class="text-green-600 hover:text-green-800 text-sm flex items-center">
                    Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Rejected Payments -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Ditolak</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $rejectedCount }}</div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-shopping-cart text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Pesanan Menunggu</div>
                    <div class="text-3xl font-bold text-gray-800">
                        {{ \App\Models\Order::where('status', 'waiting_payment')->count() }}
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('cs1.orders.pending') }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                    Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Pending Payments -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-clock mr-2"></i>Pembayaran Menunggu Verifikasi
                </h2>
                <a href="{{ route('cs1.payments.pending') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        
        @if($pendingPayments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Upload
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pendingPayments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $payment->order->order_number }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $payment->order->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment->order->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">
                                        Rp {{ number_format($payment->order->total, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $payment->created_at->format('d M Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('cs1.payments.show', $payment) }}" 
                                       class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                        <i class="fas fa-eye mr-1"></i>Verifikasi
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-check-circle text-green-400 text-4xl mb-3"></i>
                <p class="text-gray-600">Tidak ada pembayaran yang menunggu verifikasi</p>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Verification Guide -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle mr-2"></i>Panduan Verifikasi
            </h2>
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="bg-green-100 p-2 rounded-full mr-3 mt-1">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div>
                        <div class="font-medium text-gray-800">Verifikasi jika:</div>
                        <div class="text-sm text-gray-600">- Nama pengirim sesuai dengan nama customer</div>
                        <div class="text-sm text-gray-600">- Jumlah transfer sesuai dengan total pesanan</div>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="bg-red-100 p-2 rounded-full mr-3 mt-1">
                        <i class="fas fa-times text-red-600"></i>
                    </div>
                    <div>
                        <div class="font-medium text-gray-800">Tolak jika:</div>
                        <div class="text-sm text-gray-600">- Bukti transfer tidak jelas/terpotong</div>
                        <div class="text-sm text-gray-600">- Jumlah transfer tidak sesuai</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-link mr-2"></i>Quick Links
            </h2>
            <div class="space-y-3">
                <a href="{{ route('cs1.payments.pending') }}" 
                   class="flex items-center p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                    <i class="fas fa-clock text-yellow-600 mr-3"></i>
                    <div>
                        <div class="font-medium text-gray-800">Verifikasi Pembayaran</div>
                        <div class="text-sm text-gray-600">{{ $pendingCount }} menunggu</div>
                    </div>
                </a>
                <a href="{{ route('cs1.payments.verified') }}" 
                   class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <div>
                        <div class="font-medium text-gray-800">Pembayaran Terverifikasi</div>
                        <div class="text-sm text-gray-600">{{ $verifiedCount }} pembayaran</div>
                    </div>
                </a>
                <a href="{{ route('cs1.orders.pending') }}" 
                   class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <i class="fas fa-shopping-cart text-blue-600 mr-3"></i>
                    <div>
                        <div class="font-medium text-gray-800">Pesanan Menunggu</div>
                        <div class="text-sm text-gray-600">{{ \App\Models\Order::where('status', 'waiting_payment')->count() }} pesanan</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection