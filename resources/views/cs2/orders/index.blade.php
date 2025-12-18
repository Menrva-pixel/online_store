@extends('layouts.app')

@section('title', 'Kelola Pesanan - CS Layer 2')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-tasks mr-3"></i>Kelola Pesanan
                </h1>
                <p class="text-gray-600 mt-2">Proses dan kirim pesanan yang sudah diverifikasi pembayarannya.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('cs2.dashboard') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('cs2.orders.index') }}" method="GET" class="space-y-4">
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
                           placeholder="No. order, nama penerima...">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-filter mr-2"></i>Status
                    </label>
                    <select name="status" 
                            id="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="waiting_payment" {{ request('status') == 'waiting_payment' ? 'selected' : '' }}>Menunggu Bayar</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
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
                    </select>
                </div>
            </div>

            <div class="flex justify-between">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-filter mr-2"></i>Terapkan Filter
                </button>
                <a href="{{ route('cs2.orders.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-redo mr-2"></i>Reset Filter
                </a>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-500">
            <div class="text-sm text-gray-600">Menunggu Bayar</div>
            <div class="text-2xl font-bold text-gray-800">
                {{ \App\Models\Order::where('status', 'waiting_payment')->count() }}
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-500">
            <div class="text-sm text-gray-600">Diproses</div>
            <div class="text-2xl font-bold text-purple-600">
                {{ \App\Models\Order::where('status', 'processing')->count() }}
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-indigo-500">
            <div class="text-sm text-gray-600">Dikirim</div>
            <div class="text-2xl font-bold text-indigo-600">
                {{ \App\Models\Order::where('status', 'shipped')->count() }}
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
            <div class="text-sm text-gray-600">Selesai</div>
            <div class="text-2xl font-bold text-green-600">
                {{ \App\Models\Order::where('status', 'completed')->count() }}
            </div>
        </div>
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
                                Status & Timeline
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
                                    'waiting_payment' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-purple-100 text-purple-800',
                                    'shipped' => 'bg-indigo-100 text-indigo-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                                $statusLabels = [
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
                                    <div class="text-sm text-gray-600">{{ $order->recipient_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->items->count() }} item</div>
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
                                    <div class="flex items-center space-x-3">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$order->status] }}">
                                            {{ $statusLabels[$order->status] }}
                                        </span>
                                        <div class="text-xs text-gray-500">
                                            {{ $order->created_at->format('d/m') }}
                                        </div>
                                    </div>
                                    @if($order->tracking_number)
                                        <div class="text-xs text-gray-600 mt-1">
                                            <i class="fas fa-barcode mr-1"></i>{{ $order->tracking_number }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('cs2.orders.show', $order) }}" 
                                           class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>
                                        
                                        @if($order->status == 'waiting_payment')
                                            <form action="{{ route('cs2.orders.process', $order) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Proses pesanan {{ $order->order_number }}?')"
                                                        class="px-3 py-1 bg-purple-600 text-white rounded text-sm hover:bg-purple-700 transition">
                                                    <i class="fas fa-cog mr-1"></i>Proses
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($order->status == 'processing')
                                            <button onclick="showShipForm('{{ $order->id }}')"
                                                    class="px-3 py-1 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700 transition">
                                                <i class="fas fa-truck mr-1"></i>Kirim
                                            </button>
                                        @endif
                                        
                                        @if($order->status == 'shipped')
                                            <form action="{{ route('cs2.orders.complete', $order) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Tandai pesanan {{ $order->order_number }} sebagai selesai?')"
                                                        class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700 transition">
                                                    <i class="fas fa-check mr-1"></i>Selesai
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
                <p class="text-gray-600">Belum ada pesanan yang sesuai dengan filter.</p>
            </div>
        @endif
    </div>

    <!-- Ship Form Modal -->
    <div id="shipModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pengiriman</h3>
                
                <form id="shipForm" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" id="order_id">
                    
                    <div class="mb-4">
                        <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-2">
                            No. Resi / Tracking Number *
                        </label>
                        <input type="text" 
                               name="tracking_number" 
                               id="tracking_number"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Contoh: 1234567890">
                    </div>
                    
                    <div class="mb-4">
                        <label for="shipping_courier" class="block text-sm font-medium text-gray-700 mb-2">
                            Kurir Pengiriman *
                        </label>
                        <select name="shipping_courier" 
                                id="shipping_courier"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Kurir</option>
                            <option value="JNE">JNE</option>
                            <option value="J&T">J&T</option>
                            <option value="POS Indonesia">POS Indonesia</option>
                            <option value="TIKI">TIKI</option>
                            <option value="SiCepat">SiCepat</option>
                            <option value="Anteraja">Anteraja</option>
                            <option value="GoSend">GoSend</option>
                            <option value="GrabExpress">GrabExpress</option>
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" 
                                onclick="closeShipModal()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan & Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showShipForm(orderId) {
    document.getElementById('order_id').value = orderId;
    document.getElementById('shipForm').action = `/cs2/orders/${orderId}/ship`;
    document.getElementById('shipModal').classList.remove('hidden');
}

function closeShipModal() {
    document.getElementById('shipModal').classList.add('hidden');
    document.getElementById('shipForm').reset();
}

// Close modal when clicking outside
document.getElementById('shipModal').addEventListener('click', function(e) {
    if (e.target.id === 'shipModal') {
        closeShipModal();
    }
});
</script>
@endsection