@extends('layouts.app')

@section('title', 'Login - Toko Online')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <!-- Logo & Brand -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center">
                    <div class="bg-blue-600 p-3 rounded-lg">
                        <i class="fas fa-store text-white text-3xl"></i>
                    </div>
                    <span class="ml-3 text-3xl font-bold text-gray-900">Toko Online</span>
                </a>
                <h2 class="mt-6 text-center text-2xl font-bold text-gray-900">
                    Masuk ke akun Anda
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Atau 
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        daftar akun baru
                    </a>
                </p>
            </div>

            <!-- Alert Messages -->
            @if(session('error'))
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

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

        <!-- Login Form -->
        <form class="mt-8 space-y-6" action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <!-- Email Field -->
                <div class="mb-4">
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
                               autocomplete="current-password" 
                               required
                               class="pl-10 appearance-none rounded-lg relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="********">
                        <button type="button" 
                                onclick="togglePasswordVisibility()" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="password-toggle-icon" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Remember dan lupa password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" 
                           name="remember" 
                           type="checkbox"
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Ingat saya
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                        Lupa password?
                    </a>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt text-blue-500 group-hover:text-blue-400"></i>
                    </span>
                    Masuk
                </button>
            </div>
        </form>

        <!-- Demo Accounts -->
        <div class="mt-8">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Akun Demo untuk Testing:
                </h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Admin:</span>
                        <code class="bg-gray-200 px-2 py-1 rounded">admin@example.com</code>
                    </div>
                    <div class="flex justify-between">
                        <span>CS Layer 1:</span>
                        <code class="bg-gray-200 px-2 py-1 rounded">cs1@example.com</code>
                    </div>
                    <div class="flex justify-between">
                        <span>CS Layer 2:</span>
                        <code class="bg-gray-200 px-2 py-1 rounded">cs2@example.com</code>
                    </div>
                    <div class="flex justify-between">
                        <span>Customer:</span>
                        <code class="bg-gray-200 px-2 py-1 rounded">customer@example.com</code>
                    </div>
                    <div class="text-center mt-2">
                        <span class="font-medium">Password semua akun: </span>
                        <code class="bg-gray-200 px-2 py-1 rounded">password123</code>
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
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('password-toggle-icon');
    
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