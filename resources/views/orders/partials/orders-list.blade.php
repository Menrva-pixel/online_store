@if($orders->isEmpty())
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada pesanan</h3>
        <p class="mt-2 text-gray-500">Belum ada pesanan yang dibuat.</p>
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
                    <a href="{{ route('my.orders.payment', $order) }}" 
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