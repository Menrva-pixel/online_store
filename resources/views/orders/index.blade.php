@extends('layouts.app')

@section('title', 'Daftar Pesanan - Toko Online')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">
        <i class="fas fa-history mr-3"></i>Riwayat Pesanan
    </h1>

    <!-- Order Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-600">Total Pesanan</div>
            <div class="text-2xl font-bold text-gray-800">{{ auth()->user()->orders()->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-600">Belum Bayar</div>
            <div class="text-2xl font-bold text-yellow-600">{{ auth()->user()->orders()->waitingPayment()->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-600">Diproses</div>
            <div class="text-2xl font-bold text-blue-600">{{ auth()->user()->orders()->processing()->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-600">Selesai</div>
            <div class="text-2xl font-bold text-green-600">{{ auth()->user()->orders()->completed()->count() }}</div>
        </div>
    </div>

    <!-- Order Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
            <div class="flex space-x-2">
                <button data-filter="all" 
                        class="filter-btn px-4 py-2 rounded-lg bg-blue-600 text-white">
                    Semua
                </button>
                <button data-filter="waiting_payment" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Menunggu Bayar
                </button>
                <button data-filter="processing" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Diproses
                </button>
                <button data-filter="completed" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                    Selesai
                </button>
            </div>
            
            <div class="relative w-full md:w-64">
                <input type="text" 
                       id="searchOrders"
                       placeholder="Cari order ID..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
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
                        @foreach($orders as $order)
                            @php
                                $status = $order->statusLabel;
                            @endphp
                            <tr class="order-row hover:bg-gray-50" data-status="{{ $order->status }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-blue-600">{{ $order->order_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->items->count() }} item</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">{{ $order->formatted_total }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $status['color'] }}">
                                        {{ $status['text'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('orders.show', $order) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-4">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </a>
                                    @if($order->status === 'waiting_payment' && !$order->hasPaymentProof())
                                        <a href="{{ route('payment.show', $order) }}" 
                                           class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-credit-card mr-1"></i>Bayar
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-shopping-bag text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Pesanan</h3>
                <p class="text-gray-500 mb-6">Mulai belanja dan buat pesanan pertama Anda</p>
                <a href="{{ route('home') }}" 
                   class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-shopping-cart mr-2"></i>Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Filter orders
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const filter = this.getAttribute('data-filter');
        
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('bg-blue-600', 'text-white');
            b.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
        });
        this.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
        this.classList.add('bg-blue-600', 'text-white');
        
        // Filter rows
        const rows = document.querySelectorAll('.order-row');
        rows.forEach(row => {
            if (filter === 'all' || row.getAttribute('data-status') === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});

// Search orders
document.getElementById('searchOrders').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.order-row');
    
    rows.forEach(row => {
        const orderId = row.querySelector('.font-medium.text-blue-600').textContent.toLowerCase();
        if (orderId.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection