@extends('layouts.app')

@section('title', 'Register - Toko Online')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <!-- Logo & Brand -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center">
                    <div class="bg-green-600 p-3 rounded-lg">
                        <i class="fas fa-store text-white text-3xl"></i>
                    </div>
                    <span class="ml-3 text-3xl font-bold text-gray-900">Toko Online</span>
                </a>
                <h2 class="mt-6 text-center text-2xl font-bold text-gray-900">
                    Buat akun baru
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Masuk di sini
                    </a>
                </p>
            </div>

            <!-- Alert Messages -->
            @if($errors->any())
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Registration Form -->
        <form class="mt-8 space-y-6" action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm space-y-4">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2"></i>Nama Lengkap
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input id="name" 
                               name="name" 
                               type="text" 
                               autocomplete="name" 
                               required 
                               value="{{ old('name') }}"
                               class="pl-10 appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="John Doe">
                    </div>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2"></i>Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-at text-gray-400"></i>
                        </div>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               value="{{ old('email') }}"
                               class="pl-10 appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="email@example.com">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="new-password" 
                               required
                               class="pl-10 appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="********">
                        <button type="button" 
                                onclick="togglePasswordVisibility('password')" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password-toggle-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Minimal 8 karakter
                    </p>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Konfirmasi Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               autocomplete="new-password" 
                               required
                               class="pl-10 appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="********">
                        <button type="button" 
                                onclick="togglePasswordVisibility('password_confirmation')" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password-confirm-toggle-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-center">
                    <input id="terms" 
                           name="terms" 
                           type="checkbox" 
                           required
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        Saya setuju dengan 
                        <a href="#" class="text-blue-600 hover:text-blue-500">Syarat & Ketentuan</a>
                        dan 
                        <a href="#" class="text-blue-600 hover:text-blue-500">Kebijakan Privasi</a>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus text-green-500 group-hover:text-green-400"></i>
                    </span>
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <!-- Registration Benefits -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 text-center">
                <i class="fas fa-gift mr-2"></i>Keuntungan Bergabung:
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <i class="fas fa-shopping-cart text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Belanja Mudah</p>
                        <p class="text-xs text-gray-500">Akses semua produk</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <i class="fas fa-shield-alt text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Aman & Terpercaya</p>
                        <p class="text-xs text-gray-500">Data Anda terlindungi</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-purple-100 p-2 rounded-lg">
                            <i class="fas fa-truck text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Pengiriman Cepat</p>
                        <p class="text-xs text-gray-500">Pesanan cepat sampai</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="bg-yellow-100 p-2 rounded-lg">
                            <i class="fas fa-headset text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">CS 24/7</p>
                        <p class="text-xs text-gray-500">Bantuan kapan saja</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-blue-600">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Halaman Utama
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePasswordVisibility(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const iconId = fieldId === 'password' ? 'password-toggle-icon' : 'password-confirm-toggle-icon';
    const icon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
@endsection