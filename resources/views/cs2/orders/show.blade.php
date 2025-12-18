@extends('layouts.app')

@section('title', 'Detail Pesanan - CS Layer 2')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-file-invoice mr-3"></i>Detail Pesanan
                </h1>
                <p class="text-gray-600 mt-2">No. Pesanan: <strong>{{ $order->order_number }}</strong></p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex space-x-3">
                    <a href="{{ route('cs2.orders.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    @if($order->status == 'processing')
                        <button onclick="printPackingSlip()"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                            <i class="fas fa-print mr-2"></i>Cetak Packing Slip
                        </button>
                    @endif
                </div>
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
        <!-- Left Column - Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status & Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">Status Pesanan</h2>
                        @php
                            $statusColors = [
                                'waiting_payment' => 'bg-yellow-100 text-yellow-800',
                                'processing' => 'bg-purple-100 text-purple-800',
                                'shipped' => 'bg-indigo-100 text-indigo-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                            $statusLabels = [
                                'waiting_payment' => 'Menunggu Pembayaran',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan'
                            ];
                        @endphp
                        <span class="px-3 py-1 text-sm rounded-full {{ $statusColors[$order->status] }}">
                            {{ $statusLabels[$order->status] }}
                        </span>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mt-4 md:mt-0 flex space-x-2">
                        @if($order->status == 'waiting_payment')
                            <form action="{{ route('cs2.orders.process', $order) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Proses pesanan {{ $order->order_number }}?')"
                                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                    <i class="fas fa-cog mr-2"></i>Proses Pesanan
                                </button>
                            </form>
                        @endif
                        
                        @if($order->status == 'processing')
                            <button onclick="showShipForm()"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                <i class="fas fa-truck mr-2"></i>Kirim Pesanan
                            </button>
                        @endif
                        
                        @if($order->status == 'shipped')
                            <form action="{{ route('cs2.orders.complete', $order) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Tandai pesanan {{ $order->order_number }} sebagai selesai?')"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-check mr-2"></i>Tandai Selesai
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Timeline Pesanan</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="bg-gray-100 p-2 rounded-full mr-3">
                                <i class="fas fa-shopping-cart text-gray-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Pesanan Dibuat</div>
                                <div class="text-sm text-gray-600">{{ $order->created_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                        
                        @if($order->paymentProof && $order->paymentProof->verified_at)
                            <div class="flex items-start">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">Pembayaran Diverifikasi</div>
                                    <div class="text-sm text-gray-600">{{ $order->paymentProof->verified_at->format('d M Y H:i') }}</div>
                                </div>
                            </div>
                        @endif
                        
                        @if($order->processed_at)
                            <div class="flex items-start">
                                <div class="bg-purple-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-cog text-purple-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">Pesanan Diproses</div>
                                    <div class="text-sm text-gray-600">{{ $order->processed_at->format('d M Y H:i') }}</div>
                                </div>
                            </div>
                        @endif
                        
                        @if($order->shipped_at)
                            <div class="flex items-start">
                                <div class="bg-indigo-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-truck text-indigo-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">Pesanan Dikirim</div>
                                    <div class="text-sm text-gray-600">{{ $order->shipped_at->format('d M Y H:i') }}</div>
                                    @if($order->tracking_number)
                                        <div class="text-sm text-gray-700 mt-1">
                                            <i class="fas fa-barcode mr-1"></i>{{ $order->tracking_number }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800">Item Pesanan</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <div class="px-6 py-4 flex items-center">
                            <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                         alt="{{ $item->product->name }}"
                                         class="h-16 w-16 object-cover rounded-lg">
                                @else
                                    <i class="fas fa-box text-gray-400"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">{{ $item->product->name }}</div>
                                <div class="text-sm text-gray-600">
                                    {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-500">Stok tersedia: {{ $item->product->stock }}</div>
                            </div>
                            <div class="text-lg font-bold text-gray-900">
                                Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div class="text-gray-700">Subtotal</div>
                        <div class="font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <div class="text-gray-700">Ongkos Kirim</div>
                        <div class="font-medium">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-300">
                        <div class="text-xl font-bold text-gray-900">Total</div>
                        <div class="text-2xl font-bold text-gray-900">
                            Rp {{ number_format($order->total + ($order->shipping_cost ?? 0), 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pengiriman</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600">Nama Penerima</div>
                        <div class="font-medium text-gray-900">{{ $order->recipient_name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Telepon</div>
                        <div class="font-medium text-gray-900">{{ $order->recipient_phone ?? '-' }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-sm text-gray-600">Alamat Pengiriman</div>
                        <div class="font-medium text-gray-900 mt-1 p-3 bg-gray-50 rounded-lg">
                            {{ $order->shipping_address }}
                        </div>
                    </div>
                    @if($order->shipping_courier)
                        <div>
                            <div class="text-sm text-gray-600">Kurir</div>
                            <div class="font-medium text-gray-900">{{ $order->shipping_courier }}</div>
                        </div>
                    @endif
                    @if($order->tracking_number)
                        <div>
                            <div class="text-sm text-gray-600">No. Resi</div>
                            <div class="font-medium text-gray-900">{{ $order->tracking_number }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Customer & Actions -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Customer</h2>
                <div class="space-y-3">
                    <div>
                        <div class="text-sm text-gray-600">Nama</div>
                        <div class="font-medium text-gray-900">{{ $order->user->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Email</div>
                        <div class="font-medium text-gray-900">{{ $order->user->email }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Bergabung</div>
                        <div class="font-medium text-gray-900">{{ $order->user->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pembayaran</h2>
                <div class="space-y-4">
                    @if($order->paymentProof)
                        <div>
                            <div class="text-sm text-gray-600">Status Pembayaran</div>
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Terverifikasi
                            </span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Waktu Verifikasi</div>
                            <div class="font-medium text-gray-900">
                                {{ $order->paymentProof->verified_at->format('d M Y H:i') }}
                            </div>
                        </div>
                        @if($order->paymentProof->verifier)
                            <div>
                                <div class="text-sm text-gray-600">Diverifikasi oleh</div>
                                <div class="font-medium text-gray-900">{{ $order->paymentProof->verifier->name }}</div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4 text-yellow-600">
                            <i class="fas fa-clock text-3xl mb-2"></i>
                            <p>Menunggu verifikasi pembayaran</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Additional Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Aksi Lainnya</h2>
                <div class="space-y-3">
                    @if($order->status == 'processing')
                        <a href="{{ route('cs2.orders.print', $order) }}" 
                           target="_blank"
                           class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <i class="fas fa-print text-blue-600 mr-3"></i>
                            <div>
                                <div class="font-medium text-gray-800">Cetak Packing Slip</div>
                                <div class="text-sm text-gray-600">Untuk pengepakan barang</div>
                            </div>
                        </a>
                    @endif
                    
                    @if($order->status == 'shipped')
                        <a href="{{ route('cs2.orders.labels', $order) }}" 
                           class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                            <i class="fas fa-tag text-green-600 mr-3"></i>
                            <div>
                                <div class="font-medium text-gray-800">Label Pengiriman</div>
                                <div class="text-sm text-gray-600">Download label pengiriman</div>
                            </div>
                        </a>
                    @endif
                    
                    @if(!in_array($order->status, ['completed', 'cancelled']))
                        <button onclick="showCancelForm()"
                                class="w-full flex items-center p-3 bg-red-50 rounded-lg hover:bg-red-100 transition">
                            <i class="fas fa-times text-red-600 mr-3"></i>
                            <div>
                                <div class="font-medium text-gray-800">Batalkan Pesanan</div>
                                <div class="text-sm text-gray-600">Hanya jika diperlukan</div>
                            </div>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Ringkasan</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">No. Order</span>
                        <span class="font-medium">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal</span>
                        <span class="font-medium">{{ $order->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="font-medium">{{ $statusLabels[$order->status] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Item</span>
                        <span class="font-medium">{{ $order->items->sum('quantity') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ship Form Modal -->
    <div id="shipModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pengiriman</h3>
                
                <form action="{{ route('cs2.orders.ship', $order) }}" method="POST">
                    @csrf
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

    <!-- Cancel Form Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Batalkan Pesanan</h3>
                
                <form action="{{ route('cs2.orders.cancel', $order) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="cancel_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Pembatalan *
                        </label>
                        <textarea name="reason" 
                                  id="cancel_reason" 
                                  rows="4"
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="Alasan membatalkan pesanan..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" 
                                onclick="closeCancelModal()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" 
                                onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Batalkan Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showShipForm() {
    document.getElementById('shipModal').classList.remove('hidden');
}

function closeShipModal() {
    document.getElementById('shipModal').classList.add('hidden');
}

function showCancelForm() {
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

function printPackingSlip() {
    window.open("{{ route('cs2.orders.print', $order) }}", '_blank');
}

// Close modal when clicking outside
document.getElementById('shipModal')?.addEventListener('click', function(e) {
    if (e.target.id === 'shipModal') {
        closeShipModal();
    }
});

document.getElementById('cancelModal')?.addEventListener('click', function(e) {
    if (e.target.id === 'cancelModal') {
        closeCancelModal();
    }
});
</script>
@endsection