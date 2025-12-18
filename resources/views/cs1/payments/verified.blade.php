@extends('layouts.app')

@section('title', 'Pembayaran Terverifikasi - CS Layer 1')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-check-circle mr-3"></i>Pembayaran Terverifikasi
                </h1>
                <p class="text-gray-600 mt-2">Riwayat pembayaran yang sudah diverifikasi.</p>
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
        <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
            <div class="text-sm text-gray-600">Total Terverifikasi</div>
            <div class="text-2xl font-bold text-gray-800">{{ $payments->total() }}</div>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
            <div class="text-sm text-gray-600">Menunggu Verifikasi</div>
            <div class="text-2xl font-bold text-yellow-600">
                {{ \App\Models\PaymentProof::where('status', 'pending')->count() }}
            </div>
        </div>
        <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500">
            <div class="text-sm text-gray-600">Ditolak</div>
            <div class="text-2xl font-bold text-red-600">
                {{ \App\Models\PaymentProof::where('status', 'rejected')->count() }}
            </div>
        </div>
    </div>

    <!-- Search Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('cs1.payments.verified') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-2"></i>Cari
                    </label>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="No. order, nama customer...">
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2"></i>Tanggal Verifikasi
                    </label>
                    <input type="date" 
                           name="date" 
                           id="date"
                           value="{{ request('date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>
                <div>
                    <label for="verifier" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-check mr-2"></i>Verifikator
                    </label>
                    <select name="verifier" 
                            id="verifier"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Semua Verifikator</option>
                        @foreach(\App\Models\User::whereIn('role', ['cs_layer1', 'admin'])->get() as $user)
                            <option value="{{ $user->id }}" {{ request('verifier') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-between">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-filter mr-2"></i>Terapkan Filter
                </button>
                <a href="{{ route('cs1.payments.verified') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-redo mr-2"></i>Reset Filter
                </a>
            </div>
        </form>
    </div>

    <!-- Verified Payments Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-800">
                    <i class="fas fa-list mr-2"></i>Daftar Pembayaran Terverifikasi
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
                                Jumlah
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Verifikasi
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
                                    <div class="text-xs text-gray-500">{{ $payment->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($payment->order->total, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">
                                        {{ $payment->verifier->name ?? 'System' }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ $payment->verified_at->format('d M Y H:i') }}
                                    </div>
                                    @if($payment->verification_notes)
                                        <div class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-sticky-note mr-1"></i>{{ Str::limit($payment->verification_notes, 50) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'processing' => 'bg-purple-100 text-purple-800',
                                            'shipped' => 'bg-indigo-100 text-indigo-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                        ];
                                        $statusLabels = [
                                            'processing' => 'Diproses',
                                            'shipped' => 'Dikirim',
                                            'completed' => 'Selesai',
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
                                            <i class="fas fa-file-invoice mr-1"></i>Bukti
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
                {{ $payments->withQueryString()->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-file-invoice-dollar text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pembayaran terverifikasi</h3>
                <p class="text-gray-600">Belum ada pembayaran yang diverifikasi.</p>
            </div>
        @endif
    </div>

    <!-- Summary -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Verification Stats -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-bar mr-2"></i>Statistik Verifikasi
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Verifikasi Hari Ini</span>
                    <span class="font-bold text-gray-900">
                        {{ \App\Models\PaymentProof::where('status', 'verified')->whereDate('verified_at', today())->count() }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Verifikasi Bulan Ini</span>
                    <span class="font-bold text-gray-900">
                        {{ \App\Models\PaymentProof::where('status', 'verified')->whereMonth('verified_at', now()->month)->count() }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Rata-rata Waktu Verifikasi</span>
                    <span class="font-bold text-gray-900">
                        @php
                            $avgTime = \App\Models\PaymentProof::where('status', 'verified')
                                ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, verified_at)) as avg_minutes')
                                ->first()->avg_minutes ?? 0;
                        @endphp
                        {{ round($avgTime) }} menit
                    </span>
                </div>
            </div>
        </div>

        <!-- Top Verifiers -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-trophy mr-2"></i>Top Verifikator
            </h3>
            <div class="space-y-3">
                @php
                    $topVerifiers = \App\Models\PaymentProof::where('status', 'verified')
                        ->whereNotNull('verified_by')
                        ->select('verified_by', \DB::raw('COUNT(*) as count'))
                        ->groupBy('verified_by')
                        ->orderByDesc('count')
                        ->limit(5)
                        ->get();
                @endphp
                @foreach($topVerifiers as $verifier)
                    @php
                        $user = \App\Models\User::find($verifier->verified_by);
                    @endphp
                    <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded">
                        <div class="flex items-center">
                            <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $user->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">{{ $user->role ?? '-' }}</div>
                            </div>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                            {{ $verifier->count }} verifikasi
                        </span>
                    </div>
                @endforeach
                @if($topVerifiers->isEmpty())
                    <p class="text-gray-500 text-center py-4">Belum ada data verifikator</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection