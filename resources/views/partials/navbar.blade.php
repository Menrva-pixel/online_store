<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">
                    <i class="fas fa-store mr-2"></i>Toko Online
                </a>
            </div>

            <!-- Desktop Menu (Hidden on Mobile) -->
            <div class="hidden md:flex items-center space-x-4">
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

                    <!-- Desktop User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 focus:outline-none">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
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
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-cog mr-3 w-5"></i> Admin Dashboard
                                </a>
                            @elseif(Auth::user()->isCSLayer1())
                                <a href="{{ route('cs1.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-headset mr-3 w-5"></i> CS Layer 1 Dashboard
                                </a>
                            @elseif(Auth::user()->isCSLayer2())
                                <a href="{{ route('cs2.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-shipping-fast mr-3 w-5"></i> CS Layer 2 Dashboard
                                </a>
                            @endif

                            <!-- Common Links -->
                            <a href="{{ route('home') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-home mr-3 w-5"></i> Home
                            </a>
                            <a href="{{ route('my.orders.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                <i class="fas fa-history mr-3 w-5"></i> Pesanan Saya
                            </a>

                            <!-- Logout -->
                            <div class="border-t mt-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3 w-5"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Mobile Menu Button (Hamburger) -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu (Hidden by default) -->
        <div id="mobile-menu" class="md:hidden hidden border-t py-4">
            @guest
                <!-- Guest Mobile Menu -->
                <div class="space-y-3">
                    <a href="{{ route('home') }}" class="flex items-center text-gray-700 hover:text-blue-600 py-2">
                        <i class="fas fa-home mr-3 w-6"></i> Home
                    </a>
                    <a href="{{ route('login') }}" class="flex items-center text-gray-700 hover:text-blue-600 py-2">
                        <i class="fas fa-sign-in-alt mr-3 w-6"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center text-gray-700 hover:text-blue-600 py-2">
                        <i class="fas fa-user-plus mr-3 w-6"></i> Daftar
                    </a>
                </div>
            @else
                <!-- Authenticated Mobile Menu -->
                <div class="space-y-3">
                    <!-- User Info -->
                    <div class="flex items-center space-x-3 p-2 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-medium">{{ Auth::user()->name }}</div>
                            <div class="text-xs">
                                @php
                                    $roleBadge = [
                                        'admin' => 'text-purple-600 bg-purple-50',
                                        'cs_layer1' => 'text-blue-600 bg-blue-50',
                                        'cs_layer2' => 'text-green-600 bg-green-50',
                                        'customer' => 'text-gray-600 bg-gray-50'
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full {{ $roleBadge[Auth::user()->role] }}">
                                    {{ $roleNames[Auth::user()->role] ?? 'User' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Links -->
                    <div class="space-y-1">
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center text-gray-700 hover:bg-gray-100 py-2 px-3 rounded">
                                <i class="fas fa-cog mr-3 w-5"></i> Admin Dashboard
                            </a>
                        @elseif(Auth::user()->isCSLayer1())
                            <a href="{{ route('cs1.dashboard') }}" class="flex items-center text-gray-700 hover:bg-gray-100 py-2 px-3 rounded">
                                <i class="fas fa-headset mr-3 w-5"></i> CS Layer 1 Dashboard
                            </a>
                        @elseif(Auth::user()->isCSLayer2())
                            <a href="{{ route('cs2.dashboard') }}" class="flex items-center text-gray-700 hover:bg-gray-100 py-2 px-3 rounded">
                                <i class="fas fa-shipping-fast mr-3 w-5"></i> CS Layer 2 Dashboard
                            </a>
                        @endif

                        <a href="{{ route('home') }}" class="flex items-center text-gray-700 hover:bg-gray-100 py-2 px-3 rounded">
                            <i class="fas fa-home mr-3 w-5"></i> Home
                        </a>
                        
                        <a href="{{ route('cart.index') }}" class="flex items-center text-gray-700 hover:bg-gray-100 py-2 px-3 rounded">
                            <i class="fas fa-shopping-cart mr-3 w-5"></i> Keranjang
                            @if($cartCount > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                        
                        <a href="{{ route('my.orders.index') }}" class="flex items-center text-gray-700 hover:bg-gray-100 py-2 px-3 rounded">
                            <i class="fas fa-history mr-3 w-5"></i> Pesanan Saya
                        </a>
                    </div>

                    <!-- Logout -->
                    <div class="border-t pt-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full text-gray-700 hover:bg-gray-100 py-2 px-3 rounded">
                                <i class="fas fa-sign-out-alt mr-3 w-5"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</nav>

<!-- AlpineJS untuk dropdown (Include di layout utama) -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- JavaScript untuk Mobile Menu Toggle -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuIcon = mobileMenuButton.querySelector('i');

    mobileMenuButton.addEventListener('click', function() {
        // Toggle menu visibility
        mobileMenu.classList.toggle('hidden');
        
        // Change icon based on menu state
        if (mobileMenu.classList.contains('hidden')) {
            mobileMenuIcon.className = 'fas fa-bars text-xl';
        } else {
            mobileMenuIcon.className = 'fas fa-times text-xl';
        }
    });

    // Close mobile menu when clicking on a link
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', function() {
            mobileMenu.classList.add('hidden');
            mobileMenuIcon.className = 'fas fa-bars text-xl';
        });
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
            mobileMenu.classList.add('hidden');
            mobileMenuIcon.className = 'fas fa-bars text-xl';
        }
    });
});
</script>

<!-- Tambahkan style untuk dropdown arrow animation -->
<style>
.rotate-180 {
    transform: rotate(180deg);
}

/* Smooth transitions */
.transition-all {
    transition: all 0.3s ease;
}

/* Mobile menu animation */
#mobile-menu {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>