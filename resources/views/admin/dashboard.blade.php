@extends('layouts.app')

@section('title', 'Admin Dashboard - Toko Online')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-tachometer-alt mr-3"></i>Admin Dashboard
        </h1>
        <p class="text-gray-600 mt-2">Selamat datang, {{ auth()->user()->name }}! Berikut statistik toko Anda.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-box text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Total Produk</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['total_products'] }}</div>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-red-600">{{ $stats['out_of_stock'] }} Habis</span>
                <span class="mx-2">•</span>
                <span class="text-yellow-600">{{ $stats['low_stock'] }} Stok Sedikit</span>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-shopping-cart text-green-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Total Pesanan</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['total_orders'] }}</div>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-blue-600">{{ $stats['pending_orders'] }} Menunggu</span>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-users text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Total Pengguna</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</div>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <a href="{{ route('admin.users') }}" class="text-purple-600 hover:text-purple-800">
                    Lihat Semua →
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-4">
                <div class="text-sm text-gray-600">Quick Actions</div>
            </div>
            <div class="space-y-3">
                <a href="{{ route('admin.products.create') }}" 
                   class="flex items-center text-blue-600 hover:text-blue-800">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <span>Tambah Produk Baru</span>
                </a>
                <a href="{{ route('admin.products.import') }}" 
                   class="flex items-center text-green-600 hover:text-green-800">
                    <i class="fas fa-file-import mr-2"></i>
                    <span>Import Produk</span>
                </a>
                <a href="{{ route('admin.orders') }}" 
                   class="flex items-center text-purple-600 hover:text-purple-800">
                    <i class="fas fa-list mr-2"></i>
                    <span>Lihat Semua Pesanan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts & Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Status Chart -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-chart-pie mr-2"></i>Status Pesanan
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($ordersByStatus as $status => $count)
                        @php
                            $colors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'waiting_payment' => 'bg-blue-100 text-blue-800',
                                'processing' => 'bg-purple-100 text-purple-800',
                                'shipped' => 'bg-indigo-100 text-indigo-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            $labels = [
                                'pending' => 'Pending',
                                'waiting_payment' => 'Menunggu Bayar',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan'
                            ];
                        @endphp
                        <div class="text-center p-4 {{ $colors[$status] }} rounded-lg">
                            <div class="text-3xl font-bold">{{ $count }}</div>
                            <div class="text-sm mt-1">{{ $labels[$status] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Stok Sedikit
                    </h2>
                    <span class="text-sm text-gray-500">{{ $stats['low_stock'] }} produk</span>
                </div>
                
                @if($stats['low_stock_products']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['low_stock_products'] as $product)
                            <div class="flex items-center p-3 bg-red-50 rounded-lg">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-800">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-600">Stok: {{ $product->stock }}</div>
                                </div>
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.products') }}?stock=low" 
                           class="text-blue-600 hover:text-blue-800 text-sm">
                            Lihat Semua →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-green-400 text-4xl mb-3"></i>
                        <p class="text-gray-600">Semua stok aman</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-clock mr-2"></i>Pesanan Terbaru
                    </h2>
                    <a href="{{ route('admin.orders') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            
            @if($stats['recent_orders']->count() > 0)
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
                                    Total
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($stats['recent_orders'] as $order)
                                @php
                                    $status = $order->statusLabel;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->formatted_total }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $status['color'] }}">
                                            {{ $status['text'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-600">Belum ada pesanan</p>
                </div>
            @endif
        </div>
    </div>

    <!-- System Info -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- System Status -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-server mr-2"></i>Status Sistem
            </h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Laravel Version</span>
                    <span class="font-medium">{{ app()->version() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">PHP Version</span>
                    <span class="font-medium">{{ phpversion() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Environment</span>
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                        {{ app()->environment() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-link mr-2"></i>Quick Links
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.products') }}" 
                   class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center">
                    <i class="fas fa-box text-blue-600 text-2xl mb-2"></i>
                    <div class="font-medium text-gray-800">Produk</div>
                </a>
                <a href="{{ route('admin.orders') }}" 
                   class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center">
                    <i class="fas fa-shopping-cart text-green-600 text-2xl mb-2"></i>
                    <div class="font-medium text-gray-800">Pesanan</div>
                </a>
                <a href="{{ route('admin.users') }}" 
                   class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center">
                    <i class="fas fa-users text-purple-600 text-2xl mb-2"></i>
                    <div class="font-medium text-gray-800">Pengguna</div>
                </a>
                <a href="{{ route('admin.products.import') }}" 
                   class="bg-yellow-50 hover:bg-yellow-100 p-4 rounded-lg text-center">
                    <i class="fas fa-file-import text-yellow-600 text-2xl mb-2"></i>
                    <div class="font-medium text-gray-800">Import</div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection