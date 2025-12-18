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
                            <a href="{{ route('my.orders.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-history mr-2"></i> Pesanan Saya
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