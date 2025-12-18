@extends('layouts.app')

@section('title', 'Kelola Pengguna - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-users mr-3"></i>Kelola Pengguna
                </h1>
                <p class="text-gray-600 mt-2">Kelola semua pengguna sistem.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500">
                <div class="text-sm text-gray-600">Total Pengguna</div>
                <div class="text-2xl font-bold text-gray-800">{{ $users->total() }}</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-purple-500">
                <div class="text-sm text-gray-600">Admin</div>
                <div class="text-2xl font-bold text-purple-600">
                    {{ App\Models\User::where('role', 'admin')->count() }}
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
                <div class="text-sm text-gray-600">Customer</div>
                <div class="text-2xl font-bold text-green-600">
                    {{ App\Models\User::where('role', 'customer')->count() }}
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-500">
                <div class="text-sm text-gray-600">CS Layer 1</div>
                <div class="text-2xl font-bold text-yellow-600">
                    {{ App\Models\User::where('role', 'cs_layer1')->count() }}
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-red-500">
                <div class="text-sm text-gray-600">CS Layer 2</div>
                <div class="text-2xl font-bold text-red-600">
                    {{ App\Models\User::where('role', 'cs_layer2')->count() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('admin.users') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-2"></i>Cari Pengguna
                    </label>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Nama atau email...">
                </div>

                <!-- Role Filter -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tag mr-2"></i>Filter Role
                    </label>
                    <select name="role" 
                            id="role"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="all">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="cs_layer1" {{ request('role') == 'cs_layer1' ? 'selected' : '' }}>CS Layer 1</option>
                        <option value="cs_layer2" {{ request('role') == 'cs_layer2' ? 'selected' : '' }}>CS Layer 2</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sort mr-2"></i>Tampilkan per halaman
                    </label>
                    <select name="per_page" 
                            onchange="this.form.submit()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 pengguna</option>
                        <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30 pengguna</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 pengguna</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-filter mr-2"></i>Terapkan Filter
                </button>
                <a href="{{ route('admin.users') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-redo mr-2"></i>Reset Filter
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-800">
                    <i class="fas fa-list mr-2"></i>Daftar Pengguna
                </h2>
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }}
                </div>
            </div>
        </div>

        @if($users->count() > 0)
            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengguna
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Bergabung
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-gray-900">{{ $user->email }}</div>
                                    <div class="text-sm text-gray-500">
                                        @if($user->email_verified_at)
                                            <span class="text-green-600">
                                                <i class="fas fa-check-circle mr-1"></i>Terverifikasi
                                            </span>
                                        @else
                                            <span class="text-yellow-600">
                                                <i class="fas fa-clock mr-1"></i>Belum verifikasi
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.users.role', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <select name="role" 
                                                onchange="this.form.submit()"
                                                class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition 
                                                       {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 
                                                          ($user->role == 'customer' ? 'bg-green-100 text-green-800' : 
                                                          ($user->role == 'cs_layer1' ? 'bg-yellow-100 text-yellow-800' : 
                                                          'bg-red-100 text-red-800')) }}">
                                            <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="cs_layer1" {{ $user->role == 'cs_layer1' ? 'selected' : '' }}>CS Layer 1</option>
                                            <option value="cs_layer2" {{ $user->role == 'cs_layer2' ? 'selected' : '' }}>CS Layer 2</option>
                                        </select>
                                    </form>
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($user->role == 'admin')
                                            <i class="fas fa-shield-alt mr-1"></i>Akses penuh
                                        @elseif($user->role == 'cs_layer1')
                                            <i class="fas fa-headset mr-1"></i>Verifikasi pembayaran
                                        @elseif($user->role == 'cs_layer2')
                                            <i class="fas fa-shipping-fast mr-1"></i>Proses pesanan
                                        @else
                                            <i class="fas fa-shopping-cart mr-1"></i>Beli produk
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $user->created_at->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $user->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        @if($user->id != auth()->id())
                                            <button type="button"
                                                    onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')"
                                                    class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50 transition"
                                                    title="Hapus Pengguna"
                                                    {{ $user->role == 'admin' && App\Models\User::where('role', 'admin')->count() == 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <!-- Hidden Delete Form -->
                                            <form id="delete-user-{{ $user->id }}" 
                                                action="{{ route('admin.users') }}" 
                                                method="GET" class="hidden">
                                                <input type="hidden" name="delete_user" value="{{ $user->id }}">
                                            </form>
                                        @else
                                            <span class="text-gray-400 p-2" title="Tidak dapat menghapus akun sendiri">
                                                <i class="fas fa-trash"></i>
                                            </span>
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
                {{ $users->withQueryString()->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-users-slash text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada pengguna</h3>
                <p class="text-gray-600 mb-6">Tidak ditemukan pengguna yang sesuai dengan filter.</p>
                <a href="{{ route('admin.users') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center inline-flex">
                    <i class="fas fa-redo mr-2"></i>Tampilkan Semua Pengguna
                </a>
            </div>
        @endif
    </div>
</div>

<!-- JavaScript for Delete Confirmation -->
<script>
function confirmDelete(userId, userName) {
    if (confirm(`Apakah Anda yakin ingin menghapus pengguna "${userName}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
        document.getElementById(`delete-user-${userId}`).submit();
    }
}
</script>
@endsection