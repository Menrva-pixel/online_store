@extends('layouts.app')

@section('title', 'Pesanan Menunggu Pembayaran - CS Layer 1')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-shopping-cart mr-3"></i>Pesanan Menunggu Pembayaran
                </h1>
                <p class="text-gray-600 mt-2">Monitor pesanan yang belum memiliki bukti pembayaran.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('cs1.dashboard') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
            <div class="text-sm text-gray-600">Total Menunggu</div>
            <div class="text-2xl font-bold text-gray-800">{{ $orders->total() }}</div>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
            <div class="text-sm text-gray-600">Belum Upload</div>
            <div class="text-2xl font-bold text-yellow-600">
                {{ $orders->where('paymentProof', null)->count() }}
            </div>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg border-l-4 border-purple-500">
            <div class="text-sm text-gray-600">Sudah Upload</div>
            <div class="text-2xl font-bold text-purple-600">
                {{ $orders->where('paymentProof', '!=', null)->count() }}
            </div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
            <div class="text-sm text-gray-600">Sudah Diverifikasi</div>
            <div class="text-2xl font-bold text-green-600">
                {{ \App\Models\Order::where('status', 'processing')->count() }}
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('cs1.orders.pending') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-2"></i>Cari Pesanan
                    </label>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="No. order, nama customer...">
                </div>

                <!-- Payment Status -->
                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>Status Bukti
                    </label>
                    <select name="payment_status" 
                            id="payment_status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="all" {{ request('payment_status') == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="has_proof" {{ request('payment_status') == 'has_proof' ? 'selected' : '' }}>Sudah Upload Bukti</option>
                        <option value="no_proof" {{ request('payment_status') == 'no_proof' ? 'selected' : '' }}>Belum Upload Bukti</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2"></i>Tanggal Order
                    </label>
                    <input type="date" 
                           name="date" 
                           id="date"
                           value="{{ request('date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
            </div>

            <div class="flex justify-between">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-filter mr-2"></i>Terapkan Filter
                </button>
                <a href="{{ route('cs1.orders.pending') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-redo mr-2"></i>Reset Filter
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-800">
                    <i class="fas fa-list mr-2"></i>Daftar Pesanan Menunggu
                </h2>
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }}
                </div>
            </div>
        </div>

        @if($orders->count() > 0)
            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pesanan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bukti Pembayaran
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Waktu Tunggu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            @php
                                $waitingHours = $order->created_at->diffInHours(now());
                                $isOverdue = $waitingHours > 24; // Lebih dari 24 jam
                            @endphp
                            <tr class="hover:bg-gray-50 {{ $isOverdue ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $order->order_number }}</div>
                                    <div class="text-sm text-gray-600">{{ $order->recipient_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</div>
                                    @if($isOverdue)
                                        <div class="text-xs text-red-600 mt-1">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Overdue
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $order->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($order->total, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($order->paymentProof)
                                        <div class="flex items-center">
                                            <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                                <i class="fas fa-check mr-1"></i>Sudah Upload
                                            </span>
                                            <div class="text-xs text-gray-500 ml-2">
                                                {{ $order->paymentProof->created_at->format('H:i') }}
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">
                                            Menunggu verifikasi
                                        </div>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Belum Upload
                                        </span>
                                        <div class="text-xs text-gray-600 mt-1">
                                            Belum ada bukti
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium {{ $isOverdue ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ $waitingHours }} jam
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Sejak {{ $order->created_at->format('d M') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        @if($order->paymentProof)
                                            <a href="{{ route('cs1.payments.show', $order->paymentProof) }}" 
                                               class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">
                                                <i class="fas fa-file-invoice mr-1"></i>Verifikasi
                                            </a>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded text-sm cursor-not-allowed">
                                                <i class="fas fa-clock mr-1"></i>Tunggu Upload
                                            </span>
                                        @endif
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           target="_blank"
                                           class="px-3 py-1 border border-gray-300 text-gray-700 rounded text-sm hover:bg-gray-50 transition">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->withQueryString()->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pesanan menunggu</h3>
                <p class="text-gray-600">Semua pesanan sudah memiliki bukti pembayaran.</p>
            </div>
        @endif
    </div>

    <!-- Reminder Section -->
    <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
        <h3 class="text-lg font-bold text-gray-800 mb-3">
            <i class="fas fa-bell mr-2"></i>Pengingat Tindakan
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-800 mb-2">Untuk Pesanan Tanpa Bukti:</h4>
                <ul class="text-sm text-gray-700 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-envelope text-blue-500 mr-2 mt-0.5"></i>
                        <span>Kirim email reminder setelah 12 jam</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-phone text-green-500 mr-2 mt-0.5"></i>
                        <span>Hubungi customer setelah 24 jam</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-times text-red-500 mr-2 mt-0.5"></i>
                        <span>Batalkan otomatis setelah 48 jam</span>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium text-gray-800 mb-2">Untuk Pesanan dengan Bukti:</h4>
                <ul class="text-sm text-gray-700 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                        <span>Verifikasi segera setelah upload</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-clock text-yellow-500 mr-2 mt-0.5"></i>
                        <span>Maksimal 2 jam untuk verifikasi</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2 mt-0.5"></i>
                        <span>Segera tolak jika bukti tidak valid</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h4 class="font-medium text-gray-800 mb-3">Rata-rata Waktu Tunggu</h4>
            @php
                $avgWaitTime = $orders->avg(function($order) {
                    return $order->created_at->diffInHours(now());
                });
            @endphp
            <div class="text-3xl font-bold text-blue-600">{{ round($avgWaitTime ?? 0) }} jam</div>
            <div class="text-sm text-gray-600 mt-2">Waktu tunggu rata-rata</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h4 class="font-medium text-gray-800 mb-3">Pesanan Overdue</h4>
            @php
                $overdueCount = $orders->filter(function($order) {
                    return $order->created_at->diffInHours(now()) > 24;
                })->count();
            @endphp
            <div class="text-3xl font-bold text-red-600">{{ $overdueCount }}</div>
            <div class="text-sm text-gray-600 mt-2">Lebih dari 24 jam</div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h4 class="font-medium text-gray-800 mb-3">Pesanan Baru Hari Ini</h4>
            @php
                $todayOrders = \App\Models\Order::where('status', 'waiting_payment')
                    ->whereDate('created_at', today())
                    ->count();
            @endphp
            <div class="text-3xl font-bold text-green-600">{{ $todayOrders }}</div>
            <div class="text-sm text-gray-600 mt-2">Order dibuat hari ini</div>
        </div>
    </div>
</div>
@endsection