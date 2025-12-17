<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Online')</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">
                        <i class="fas fa-store mr-2"></i>Toko Online
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-user-plus mr-1"></i> Daftar
                        </a>
                    @else
                        <!-- Cart Icon -->
                        <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600 relative">
                            <i class="fas fa-shopping-cart"></i>
                            @php
                                $cartCount = auth()->user()->carts()->count();
                            @endphp
                            @if($cartCount > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                        
                        <!-- User Menu -->
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 focus:outline-none">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 hidden group-hover:block hover:block">
                                <!-- Role Badge -->
                                <div class="px-4 py-2 border-b">
                                    @php
                                        $roleColors = [
                                            'admin' => 'bg-purple-100 text-purple-800',
                                            'cs_layer1' => 'bg-blue-100 text-blue-800',
                                            'cs_layer2' => 'bg-green-100 text-green-800',
                                            'customer' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $roleNames = [
                                            'admin' => 'Admin',
                                            'cs_layer1' => 'CS Layer 1',
                                            'cs_layer2' => 'CS Layer 2',
                                            'customer' => 'Customer'
                                        ];
                                    @endphp
                                    <span class="text-xs px-2 py-1 rounded-full {{ $roleColors[Auth::user()->role] }}">
                                        {{ $roleNames[Auth::user()->role] }}
                                    </span>
                                </div>
                                
                                <!-- Dashboard Links Based on Role -->
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2"></i> Admin Dashboard
                                    </a>
                                @elseif(Auth::user()->isCSLayer1())
                                    <a href="{{ route('cs1.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-headset mr-2"></i> CS Layer 1 Dashboard
                                    </a>
                                @elseif(Auth::user()->isCSLayer2())
                                    <a href="{{ route('cs2.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-shipping-fast mr-2"></i> CS Layer 2 Dashboard
                                    </a>
                                @endif
                                
                                <!-- Common Links -->
                                <a href="{{ route('home') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-home mr-2"></i> Home
                                </a>
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-history mr-2"></i> Riwayat Pesanan
                                </a>
                                
                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-lg mt-8">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-bold text-lg text-gray-800 mb-4">
                        <i class="fas fa-store mr-2"></i>Toko Online
                    </h3>
                    <p class="text-gray-600">
                        Platform belanja online terpercaya dengan berbagai produk berkualitas.
                    </p>
                </div>
                
                <div>
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600">Home</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600">Login</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-600 hover:text-blue-600">Register</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-bold text-lg text-gray-800 mb-4">Kontak</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                            <span class="text-gray-600">support@tokoonline.com</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone text-gray-400 mr-2"></i>
                            <span class="text-gray-600">+62 812 3456 7890</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-200 mt-8 pt-8 text-center">
                <p class="text-gray-600">
                    &copy; {{ date('Y') }} Toko Online Sederhana. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>