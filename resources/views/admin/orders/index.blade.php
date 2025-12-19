@extends('layouts.app')

@section('title', 'Kelola Pesanan - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-shopping-cart mr-3"></i>Kelola Pesanan
                </h1>
                <p class="text-gray-600 mt-2">Kelola semua pesanan dari pelanggan.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500">
                <div class="text-sm text-gray-600">Total Pesanan</div>
                <div class="text-2xl font-bold text-gray-800">{{ $orders->total() }}</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-500">
                <div class="text-sm text-gray-600">Pending</div>
                <div class="text-2xl font-bold text-yellow-600">
                    {{ \App\Models\Order::whereIn('status', ['pending', 'waiting_payment'])->count() }}
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-500">
                <div class="text-sm text-gray-600">Diproses</div>
                <div class="text-2xl font-bold text-purple-600">
                    {{ \App\Models\Order::where('status', 'processing')->count() }}
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
                <div class="text-sm text-gray-600">Selesai</div>
                <div class="text-2xl font-bold text-green-600">
                    {{ \App\Models\Order::where('status', 'completed')->count() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="space-y-4">
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
                           placeholder="No. pesanan, nama penerima...">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-filter mr-2"></i>Filter Status
                    </label>
                    <select name="status" 
                            id="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="waiting_payment" {{ request('status') == 'waiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sort mr-2"></i>Urutkan
                    </label>
                    <select name="sort" 
                            id="sort"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                        <option value="total" {{ request('sort') == 'total' ? 'selected' : '' }}>Total (Rendah-Tinggi)</option>
                        <option value="recipient_name" {{ request('sort') == 'recipient_name' ? 'selected' : '' }}>Nama Penerima</option>
                    </select>
                    <select name="direction" 
                            class="w-full mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="desc" {{ request('direction', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-filter mr-2"></i>Terapkan Filter
                </button>
                <a href="{{ route('admin.orders.index') }}" 
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
                    <i class="fas fa-list mr-2"></i>Daftar Pesanan
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
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
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
                                    'waiting_payment' => 'Menunggu Bayar',
                                    'processing' => 'Diproses',
                                    'shipped' => 'Dikirim',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan'
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $order->order_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->recipient_name }}</div>
                                    <div class="text-xs text-gray-400">{{ $order->items->count() }} item</div>
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                    @if($order->paymentProof)
                                        <div class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-file-invoice-dollar mr-1"></i>Bukti terupload
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $order->created_at->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $order->created_at->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50 transition"
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($order->status == 'pending' || $order->status == 'waiting_payment')
                                            <form action="{{ route('admin.orders.status.update', $order) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        name="status" 
                                                        value="cancelled"
                                                        onclick="return confirm('Batalkan pesanan {{ $order->order_number }}?')"
                                                        class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50 transition"
                                                        title="Batalkan">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
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
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-shopping-cart text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pesanan</h3>
                <p class="text-gray-600 mb-6">Belum ada pesanan yang sesuai dengan filter.</p>
                <a href="{{ route('admin.orders') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center inline-flex">
                    <i class="fas fa-redo mr-2"></i>Tampilkan Semua Pesanan
                </a>
            </div>
        @endif
    </div>

    <!-- Status Legend -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4">
            <i class="fas fa-info-circle mr-2"></i>Legenda Status
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="flex items-center">
                <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                <span class="text-sm text-gray-700">Pending</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                <span class="text-sm text-gray-700">Menunggu Bayar</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 rounded-full bg-purple-500 mr-2"></span>
                <span class="text-sm text-gray-700">Diproses</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 rounded-full bg-indigo-500 mr-2"></span>
                <span class="text-sm text-gray-700">Dikirim</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                <span class="text-sm text-gray-700">Selesai</span>
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                <span class="text-sm text-gray-700">Dibatalkan</span>
            </div>
        </div>
    </div>
</div>
@endsection