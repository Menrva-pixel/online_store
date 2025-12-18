@extends('layouts.app')

@section('title', 'Pembayaran Menunggu Verifikasi - CS Layer 1')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-clock mr-3"></i>Pembayaran Menunggu Verifikasi
                </h1>
                <p class="text-gray-600 mt-2">Verifikasi bukti pembayaran dari customer.</p>
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
            <div class="text-sm text-gray-600">Total Menunggu</div>
            <div class="text-2xl font-bold text-gray-800">{{ $payments->total() }}</div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
            <div class="text-sm text-gray-600">Terverifikasi</div>
            <div class="text-2xl font-bold text-green-600">
                {{ \App\Models\PaymentProof::where('status', 'verified')->count() }}
            </div>
        </div>
        <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500">
            <div class="text-sm text-gray-600">Ditolak</div>
            <div class="text-2xl font-bold text-red-600">
                {{ \App\Models\PaymentProof::where('status', 'rejected')->count() }}
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-800">
                    <i class="fas fa-list mr-2"></i>Daftar Pembayaran
                </h2>
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $payments->firstItem() }} - {{ $payments->lastItem() }} dari {{ $payments->total() }}
                </div>
            </div>
        </div>

        @if($payments->count() > 0)
            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order & Customer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah Transfer
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Upload
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status Order
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $payment->order->order_number }}</div>
                                    <div class="text-sm text-gray-600">{{ $payment->order->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $payment->order->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($payment->order->total, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $payment->created_at->format('d M Y H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $payment->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'waiting_payment' => 'bg-blue-100 text-blue-800',
                                            'processing' => 'bg-purple-100 text-purple-800',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pending',
                                            'waiting_payment' => 'Menunggu Bayar',
                                            'processing' => 'Diproses',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$payment->order->status] ?? 'bg-gray-100' }}">
                                        {{ $statusLabels[$payment->order->status] ?? $payment->order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('cs1.payments.show', $payment) }}" 
                                           class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </a>
                                        <a href="{{ route('cs1.payments.view', $payment) }}" 
                                           target="_blank"
                                           class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700 transition">
                                            <i class="fas fa-file-invoice mr-1"></i>Lihat Bukti
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
                {{ $payments->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pembayaran yang menunggu</h3>
                <p class="text-gray-600">Semua pembayaran telah diverifikasi.</p>
            </div>
        @endif
    </div>

    <!-- Verification Guide -->
    <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
        <h3 class="text-lg font-bold text-gray-800 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Panduan Verifikasi
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h4 class="font-medium text-green-700 mb-2">✅ Verifikasi jika:</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Nama pengirim sesuai dengan nama customer</li>
                    <li>• Jumlah transfer sesuai dengan total pesanan</li>
                    <li>• Tanggal transfer sesuai dengan tanggal order</li>
                    <li>• Bukti transfer jelas dan lengkap</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium text-red-700 mb-2">❌ Tolak jika:</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Bukti transfer tidak jelas/terpotong</li>
                    <li>• Jumlah transfer tidak sesuai</li>
                    <li>• Nama pengirim berbeda dengan nama customer</li>
                    <li>• Transfer dilakukan dari bank yang tidak sesuai</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection