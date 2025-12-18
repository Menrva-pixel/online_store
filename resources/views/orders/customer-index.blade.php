@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Pesanan Saya</h1>
                <p class="text-gray-600 mt-1">Kelola dan lacak pesanan Anda di sini</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Beranda
                </a>
                <a href="{{ route('cart.index') }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Keranjang
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->orders()->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Menunggu Bayar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->orders()->where('status', 'waiting_payment')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Diproses</p>
                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->orders()->where('status', 'processing')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Selesai</p>
                    <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->orders()->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Status Filter -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('my.orders.index') }}" 
                       class="px-4 py-2 rounded-lg {{ !request('status') || request('status') == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Semua
                    </a>
                    @foreach(['waiting_payment' => 'Menunggu Bayar', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $key => $label)
                    <a href="{{ route('my.orders.index', ['status' => $key]) }}" 
                       class="px-4 py-2 rounded-lg {{ request('status') == $key ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
                
                <!-- Search -->
                <div class="w-full md:w-auto">
                    <form action="{{ route('my.orders.index') }}" method="GET" class="flex">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari pesanan..."
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div id="orders-container">
        @if($orders->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada pesanan</h3>
                <p class="mt-2 text-gray-500">Belum ada pesanan yang dibuat.</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Mulai Berbelanja
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6">
                    <!-- Order Header -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $order->order_number }}</h3>
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->status_label['color'] }}">
                                    {{ $order->status_label['text'] }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $order->created_at->format('d M Y, H:i') }} • 
                                {{ $order->items->count() }} item
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-lg font-bold text-gray-900">{{ $order->formatted_total_with_shipping }}</span>
                            <a href="{{ route('my.orders.show', $order) }}" 
                               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center">
                                Detail
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Order Items Preview -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-gray-700">Items</p>
                            <p class="text-sm text-gray-500">{{ $order->items->sum('quantity') }} barang</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($order->items->take(3) as $item)
                            <div class="flex items-center bg-gray-50 rounded-lg p-2">
                                @php
                                    $productImage = optional($item->product->images)->first();
                                @endphp
                                
                                @if($productImage && $productImage->image_path)
                                <img src="{{ asset('storage/' . $productImage->image_path) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="h-10 w-10 object-cover rounded">
                                @else
                                <div class="h-10 w-10 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                @endif
                                <div class="ml-2">
                                    <p class="text-sm font-medium text-gray-900 truncate max-w-[120px]">
                                        {{ $item->product->name ?? 'Produk tidak tersedia' }}
                                    </p>
                                    <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($order->items->count() > 3)
                            <div class="flex items-center bg-gray-50 rounded-lg p-2">
                                <div class="h-10 w-10 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-600">+{{ $order->items->count() - 3 }}</span>
                                </div>
                                <p class="ml-2 text-sm text-gray-600">Lainnya</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Shipping Info -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Pengiriman</p>
                                <p class="text-sm text-gray-600">{{ $order->recipient_name }}</p>
                                <p class="text-sm text-gray-500">{{ $order->shipping_address }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Pembayaran</p>
                                <p class="text-sm text-gray-600 capitalize">
                                    {{ str_replace('_', ' ', $order->payment_method) }}
                                </p>
                                @if($order->paymentProof)
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                        {{ $order->paymentProof->status == 'verified' ? 'bg-green-100 text-green-800' : 
                                           ($order->paymentProof->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        @if($order->paymentProof->status == 'verified')
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Terverifikasi
                                        @elseif($order->paymentProof->status == 'pending')
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Menunggu
                                        @else
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Ditolak
                                        @endif
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('my.orders.show', $order) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat Detail
                            </a>
                            
                            @if($order->status == 'waiting_payment' && $order->payment_method != 'cod')
                            <a href="{{ route('orders.payment', $order) }}" 
                               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Upload Bukti Bayar
                            </a>
                            @endif
                            
                            @if($order->tracking_number)
                            <button onclick="trackOrder('{{ $order->tracking_number }}', '{{ $order->shipping_courier }}')"
                                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Lacak
                            </button>
                            @endif
                            
                            @if(in_array($order->status, ['waiting_payment', 'pending']))
                            <form action="{{ route('my.orders.cancel', $order) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Yakin ingin membatalkan pesanan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Batalkan
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
        @endif
        @endif
    </div>
</div>

<!-- Quick View Modal -->
<div id="orderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 mx-auto p-4 w-full max-w-4xl">
        <div class="bg-white rounded-xl shadow-lg">
            <div class="p-6">
                <!-- Modal content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
function trackOrder(trackingNumber, courier) {
    let trackingUrl = '';
    courier = courier ? courier.toLowerCase() : '';
    
    if (courier.includes('jne')) {
        trackingUrl = `https://www.jne.co.id/id/tracking/trace?awb=${trackingNumber}`;
    } else if (courier.includes('tiki')) {
        trackingUrl = `https://tiki.id/id/tracking?q=${trackingNumber}`;
    } else if (courier.includes('pos')) {
        trackingUrl = `https://www.posindonesia.co.id/id/tracking?resi=${trackingNumber}`;
    } else {
        trackingUrl = '#';
        alert('URL tracking tidak tersedia untuk kurir ini');
        return;
    }
    
    window.open(trackingUrl, '_blank');
}

// AJAX Filtering
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const statusLinks = document.querySelectorAll('a[href*="status="]');
    
    // Filter by status
    statusLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.getAttribute('href').includes('status=')) {
                e.preventDefault();
                const status = this.getAttribute('href').split('status=')[1];
                filterOrders(status);
            }
        });
    });
    
    // Search with debounce
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchValue = this.value.trim();
                if (searchValue.length >= 2 || searchValue.length === 0) {
                    searchOrders(searchValue);
                }
            }, 500);
        });
    }
});

function filterOrders(status) {
    fetch(`{{ route('my.orders.index') }}?status=${status}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const ordersContainer = doc.querySelector('#orders-container');
        if (ordersContainer) {
            document.querySelector('#orders-container').innerHTML = ordersContainer.innerHTML;
        }
        updateActiveFilter(status);
    });
}

function searchOrders(search) {
    fetch(`{{ route('my.orders.index') }}?search=${search}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const ordersContainer = doc.querySelector('#orders-container');
        if (ordersContainer) {
            document.querySelector('#orders-container').innerHTML = ordersContainer.innerHTML;
        }
    });
}

function updateActiveFilter(status) {
    document.querySelectorAll('a[href*="status="]').forEach(link => {
        const linkStatus = link.getAttribute('href').includes('status=') 
            ? link.getAttribute('href').split('status=')[1] 
            : 'all';
        
        link.classList.remove('bg-blue-600', 'text-white');
        link.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
        
        if ((status === 'all' && link.getAttribute('href') === '{{ route('my.orders.index') }}') || 
            linkStatus === status) {
            link.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            link.classList.add('bg-blue-600', 'text-white');
        }
    });
}

// Quick view order
function viewOrder(orderId) {
    fetch(`/my/orders/${orderId}/quick-view`)
        .then(response => response.json())
        .then(data => {
            const modal = document.getElementById('orderModal');
            const modalContent = modal.querySelector('.p-6');
            modalContent.innerHTML = data.html;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        });
}

function closeOrderModal() {
    document.getElementById('orderModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking outside
document.getElementById('orderModal').addEventListener('click', function(e) {
    if (e.target.id === 'orderModal') {
        closeOrderModal();
    }
});
</script>
@endsection