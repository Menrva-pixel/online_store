@extends('layouts.app')

@section('title', 'CS Layer 2 Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-shipping-fast mr-3"></i>CS Layer 2 Dashboard
        </h1>
        <p class="text-gray-600 mt-2">Selamat datang, {{ auth()->user()->name }}! Kelola pengiriman pesanan.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <!-- Today's Orders -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-calendar-day text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Hari Ini</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['today_orders'] }}</div>
                </div>
            </div>
        </div>

        <!-- Waiting Payment -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Menunggu Bayar</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['waiting_payment'] }}</div>
                </div>
            </div>
        </div>

        <!-- Processing -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-cog text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Diproses</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['processing'] }}</div>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('cs2.orders.index') }}?status=processing" 
                   class="text-purple-600 hover:text-purple-800 text-sm flex items-center">
                    Lihat <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Shipped -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center">
                <div class="bg-indigo-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-truck text-indigo-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Dikirim</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['shipped'] }}</div>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('cs2.orders.index') }}?status=shipped" 
                   class="text-indigo-600 hover:text-indigo-800 text-sm flex items-center">
                    Lihat <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Selesai</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['completed'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Processing Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Processing Orders -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-cog mr-2"></i>Pesanan Diproses
                    </h2>
                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                        {{ $stats['processing'] }} pesanan
                    </span>
                </div>
            </div>
            
            @if($processingOrders->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($processingOrders as $order)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $order->order_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->user->name }}</div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('cs2.orders.show', $order) }}" 
                                       class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                                        Detail
                                    </a>
                                    <a href="{{ route('cs2.orders.show', $order) }}#ship-form" 
                                       class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                                        Kirim
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 border-t border-gray-200 text-center">
                    <a href="{{ route('cs2.orders.index') }}?status=processing" 
                       class="text-blue-600 hover:text-blue-800 text-sm">
                        Lihat Semua Pesanan Diproses <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-green-400 text-4xl mb-3"></i>
                    <p class="text-gray-600">Tidak ada pesanan yang sedang diproses</p>
                </div>
            @endif
        </div>

        <!-- Shipped Orders -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-truck mr-2"></i>Pesanan Dikirim
                    </h2>
                    <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800">
                        {{ $stats['shipped'] }} pesanan
                    </span>
                </div>
            </div>
            
            @if($shippedOrders->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($shippedOrders as $order)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $order->order_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->user->name }}</div>
                                    @if($order->tracking_number)
                                        <div class="text-xs text-gray-400 mt-1">
                                            <i class="fas fa-barcode mr-1"></i>{{ $order->tracking_number }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('cs2.orders.show', $order) }}" 
                                       class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                                        Detail
                                    </a>
                                    <form action="{{ route('cs2.orders.complete', $order) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Tandai pesanan {{ $order->order_number }} sebagai selesai?')"
                                                class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">
                                            Selesai
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 border-t border-gray-200 text-center">
                    <a href="{{ route('cs2.orders.index') }}?status=shipped" 
                       class="text-blue-600 hover:text-blue-800 text-sm">
                        Lihat Semua Pesanan Dikirim <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-truck text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-600">Tidak ada pesanan yang sedang dikirim</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-bolt mr-2"></i>Quick Actions
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('cs2.orders.index') }}" 
               class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition">
                <i class="fas fa-list text-blue-600 text-2xl mb-2"></i>
                <div class="font-medium text-gray-800">Semua Pesanan</div>
            </a>
            <a href="{{ route('cs2.orders.index') }}?status=waiting_payment" 
               class="bg-yellow-50 hover:bg-yellow-100 p-4 rounded-lg text-center transition">
                <i class="fas fa-clock text-yellow-600 text-2xl mb-2"></i>
                <div class="font-medium text-gray-800">Menunggu Bayar</div>
            </a>
            <a href="{{ route('cs2.orders.index') }}?status=processing" 
               class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition">
                <i class="fas fa-cog text-purple-600 text-2xl mb-2"></i>
                <div class="font-medium text-gray-800">Diproses</div>
            </a>
            <a href="{{ route('cs2.orders.index') }}?status=shipped" 
               class="bg-indigo-50 hover:bg-indigo-100 p-4 rounded-lg text-center transition">
                <i class="fas fa-truck text-indigo-600 text-2xl mb-2"></i>
                <div class="font-medium text-gray-800">Dikirim</div>
            </a>
        </div>
    </div>
</div>
@endsection