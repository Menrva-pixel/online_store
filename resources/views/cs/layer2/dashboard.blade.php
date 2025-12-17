@extends('layouts.app')

@section('title', 'CS Layer 2 Dashboard - Toko Online')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-shipping-fast mr-3"></i>CS Layer 2 Dashboard
                </h1>
                <p class="text-gray-600 mt-2">Selamat datang, {{ auth()->user()->name }}! Proses pengiriman pesanan.</p>
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                    <i class="fas fa-truck mr-1"></i>Shipping & Delivery
                </span>
                <span class="text-gray-500">•</span>
                <span class="text-sm text-gray-600">{{ now()->format('d M Y H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Processing Orders -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-cogs text-purple-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Sedang Diproses</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['processing'] }}</div>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('cs2.orders') }}?status=processing" class="text-purple-600 hover:text-purple-800 text-sm">
                    <i class="fas fa-list mr-1"></i>Lihat Semua
                </a>
            </div>
        </div>

        <!-- Shipped Orders -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-truck text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Sudah Dikirim</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['shipped'] }}</div>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('cs2.orders') }}?status=shipped" class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-eye mr-1"></i>Lihat Detail
                </a>
            </div>
        </div>

        <!-- Completed Today -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Selesai Hari Ini</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['completed_today'] }}</div>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm">
                    <i class="fas fa-chart-line mr-1"></i>Target tercapai
                </span>
            </div>
        </div>

        <!-- Pending Shipment -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Menunggu Pengiriman</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['pending_shipment'] }}</div>
                </div>
            </div>
            <div class="mt-4">
                <a href="#pending-orders" class="text-yellow-600 hover:text-yellow-800 text-sm">
                    <i class="fas fa-arrow-right mr-1"></i>Proses Sekarang
                </a>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div id="pending-orders" class="mb-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-clipboard-list mr-2"></i>Pesanan yang Perlu Diproses
                    </h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('cs2.orders') }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-list mr-1"></i>Semua Pesanan
                        </a>
                        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">
                            {{ $orders->total() }} total
                        </span>
                    </div>
                </div>
            </div>
            
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Item
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
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $order->order_number }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $order->created_at->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            Total: {{ $order->formatted_total }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $order->recipient_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $order->recipient_phone }}
                                        </div>
                                        <div class="text-xs text-gray-400 truncate max-w-xs">
                                            {{ $order->shipping_address }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ $order->items->count() }} item
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @foreach($order->items->take(2) as $item)
                                                {{ $item->product->name }},
                                            @endforeach
                                            @if($order->items->count() > 2)
                                                +{{ $order->items->count() - 2 }} lainnya
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $status = $order->statusLabel;
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $status['color'] }}">
                                            {{ $status['text'] }}
                                        </span>
                                        @if($order->tracking_number)
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $order->tracking_number }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex flex-col space-y-2">
                                            @if($order->status === 'processing')
                                                <button onclick="showShippingModal({{ $order->id }})"
                                                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-xs">
                                                    <i class="fas fa-truck mr-1"></i>Kirim
                                                </button>
                                                <a href="{{ route('cs2.order.print', $order) }}" 
                                                   target="_blank"
                                                   class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700 text-xs">
                                                    <i class="fas fa-print mr-1"></i>Print Slip
                                                </a>
                                            @elseif($order->status === 'shipped')
                                                <form action="{{ route('cs2.order.complete', $order) }}" 
                                                      method="POST"
                                                      onsubmit="return confirm('Tandai pesanan sebagai selesai?')"
                                                      class="inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-xs">
                                                        <i class="fas fa-check mr-1"></i>Selesai
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('cs2.order.show', $order) }}" 
                                               class="bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700 text-xs">
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
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-check-circle text-green-400 text-6xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Semua Pesanan Telah Diproses</h3>
                    <p class="text-gray-500 mb-6">Tidak ada pesanan yang menunggu untuk diproses</p>
                    <a href="{{ route('cs2.orders') }}" 
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-history mr-2"></i>Lihat Riwayat Pesanan
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Shipping Guidelines -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
        <h3 class="font-bold text-green-800 mb-4">
            <i class="fas fa-info-circle mr-2"></i>Panduan Proses Pengiriman
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg">
                <div class="text-center mb-3">
                    <div class="bg-blue-100 text-blue-800 w-8 h-8 rounded-full flex items-center justify-center mx-auto">
                        1
                    </div>
                </div>
                <h4 class="font-medium text-gray-800 text-center mb-2">Packing</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Gunakan bubble wrap</li>
                    <li>• Pakai dus yang sesuai</li>
                    <li>• Checklist item</li>
                </ul>
            </div>
            
            <div class="bg-white p-4 rounded-lg">
                <div class="text-center mb-3">
                    <div class="bg-blue-100 text-blue-800 w-8 h-8 rounded-full flex items-center justify-center mx-auto">
                        2
                    </div>
                </div>
                <h4 class="font-medium text-gray-800 text-center mb-2">Labeling</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Print packing slip</li>
                    <li>• Tempel shipping label</li>
                    <li>• Tambahkan fragile sticker</li>
                </ul>
            </div>
            
            <div class="bg-white p-4 rounded-lg">
                <div class="text-center mb-3">
                    <div class="bg-blue-100 text-blue-800 w-8 h-8 rounded-full flex items-center justify-center mx-auto">
                        3
                    </div>
                </div>
                <h4 class="font-medium text-gray-800 text-center mb-2">Shipping</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Pilih kurir terbaik</li>
                    <li>• Update tracking number</li>
                    <li>• Konfirmasi pickup</li>
                </ul>
            </div>
            
            <div class="bg-white p-4 rounded-lg">
                <div class="text-center mb-3">
                    <div class="bg-blue-100 text-blue-800 w-8 h-8 rounded-full flex items-center justify-center mx-auto">
                        4
                    </div>
                </div>
                <h4 class="font-medium text-gray-800 text-center mb-2">Tracking</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Monitor pengiriman</li>
                    <li>• Update status realtime</li>
                    <li>• Konfirmasi penerimaan</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-bold text-gray-800 mb-4">Kurir Tersedia</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">JNE</span>
                    <span class="font-medium">Reguler, YES, OKE</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">SiCepat</span>
                    <span class="font-medium">REG, HALU</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">GoSend</span>
                    <span class="font-medium">Same Day</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-bold text-gray-800 mb-4">Waktu Pengiriman</h3>
            <div class="space-y-3">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-gray-600">Same Day</span>
                    <span class="ml-auto font-medium">3-5 jam</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                    <span class="text-gray-600">Next Day</span>
                    <span class="ml-auto font-medium">1-2 hari</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                    <span class="text-gray-600">Reguler</span>
                    <span class="ml-auto font-medium">3-7 hari</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-bold text-gray-800 mb-4">Tools & Resources</h3>
            <div class="space-y-3">
                <a href="#" class="flex items-center text-blue-600 hover:text-blue-800">
                    <i class="fas fa-print mr-3"></i>
                    <span>Print Shipping Labels</span>
                </a>
                <a href="#" class="flex items-center text-green-600 hover:text-green-800">
                    <i class="fas fa-file-invoice mr-3"></i>
                    <span>Packing Checklist</span>
                </a>
                <a href="#" class="flex items-center text-purple-600 hover:text-purple-800">
                    <i class="fas fa-headset mr-3"></i>
                    <span>Kontak Kurir</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Shipping Modal -->
<div id="shippingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <form id="shippingForm" method="POST">
                @csrf
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-truck text-blue-600 mr-2"></i>Proses Pengiriman
                    </h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Tracking
                        </label>
                        <input type="text" 
                               name="tracking_number" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Contoh: JNE1234567890">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kurir
                        </label>
                        <select name="shipping_carrier" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Kurir</option>
                            <option value="JNE">JNE</option>
                            <option value="SiCepat">SiCepat</option>
                            <option value="GoSend">GoSend</option>
                            <option value="GrabExpress">GrabExpress</option>
                            <option value="Tiki">Tiki</option>
                            <option value="Pos Indonesia">Pos Indonesia</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Kirim
                            </label>
                            <input type="date" 
                                   name="shipping_date" 
                                   value="{{ date('Y-m-d') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Estimasi Sampai
                            </label>
                            <input type="date" 
                                   name="estimated_delivery"
                                   value="{{ date('Y-m-d', strtotime('+3 days')) }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="closeShippingModal()"
                                class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Proses Pengiriman
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentOrderId = null;

function showShippingModal(orderId) {
    currentOrderId = orderId;
    const form = document.getElementById('shippingForm');
    form.action = `/cs2/orders/${orderId}/ship`;
    document.getElementById('shippingModal').classList.remove('hidden');
}

function closeShippingModal() {
    document.getElementById('shippingModal').classList.add('hidden');
    currentOrderId = null;
}

// Close modal on click outside
document.getElementById('shippingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeShippingModal();
    }
});

// Auto-fill estimated delivery date
document.querySelector('input[name="shipping_date"]').addEventListener('change', function(e) {
    const shippingDate = new Date(e.target.value);
    const estimatedDate = new Date(shippingDate);
    estimatedDate.setDate(estimatedDate.getDate() + 3);
    
    const formattedDate = estimatedDate.toISOString().split('T')[0];
    document.querySelector('input[name="estimated_delivery"]').value = formattedDate;
});
</script>
@endpush
@endsection