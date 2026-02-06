<nav class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/80 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold text-slate-900">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white">
                        <i class="fas fa-store text-sm"></i>
                    </span>
                    <span class="tracking-tight">Toko Online</span>
                </a>
                <span class="ml-3 hidden rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 md:inline-flex">
                    Estetik. Aman. Cepat.
                </span>
            </div>

            <!-- Desktop Menu (Hidden on Mobile) -->
            <div class="hidden md:flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-slate-700 hover:text-emerald-700">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="rounded-full bg-emerald-600 text-white px-5 py-2 font-semibold shadow-sm hover:bg-emerald-700">
                        <i class="fas fa-user-plus mr-1"></i> Daftar
                    </a>
                @else
                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="relative text-slate-700 hover:text-emerald-700">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        @php
                            $cartCount = auth()->user()->carts()->count();
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-amber-500 text-xs text-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Desktop User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-slate-700 hover:text-emerald-700 focus:outline-none">
                            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-emerald-700"></i>
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
                            class="absolute right-0 mt-2 w-60 rounded-2xl border border-slate-200 bg-white/95 py-2 shadow-xl backdrop-blur">
                            <!-- Role Badge -->
                            <div class="px-4 py-2 border-b border-slate-100">
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-slate-100 text-slate-700',
                                        'cs_layer1' => 'bg-cyan-100 text-cyan-800',
                                        'cs_layer2' => 'bg-emerald-100 text-emerald-800',
                                        'customer' => 'bg-amber-100 text-amber-800'
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
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-slate-700 hover:bg-slate-100 transition-colors">
                                    <i class="fas fa-cog mr-3 w-5"></i> Admin Dashboard
                                </a>
                            @elseif(Auth::user()->isCSLayer1())
                                <a href="{{ route('cs1.dashboard') }}" class="flex items-center px-4 py-2 text-slate-700 hover:bg-slate-100 transition-colors">
                                    <i class="fas fa-headset mr-3 w-5"></i> CS Layer 1 Dashboard
                                </a>
                            @elseif(Auth::user()->isCSLayer2())
                                <a href="{{ route('cs2.dashboard') }}" class="flex items-center px-4 py-2 text-slate-700 hover:bg-slate-100 transition-colors">
                                    <i class="fas fa-shipping-fast mr-3 w-5"></i> CS Layer 2 Dashboard
                                </a>
                            @endif

                            <!-- Common Links -->
                            <a href="{{ route('home') }}" class="flex items-center px-4 py-2 text-slate-700 hover:bg-slate-100 transition-colors">
                                <i class="fas fa-home mr-3 w-5"></i> Home
                            </a>
                            <a href="{{ route('my.orders.index') }}" class="flex items-center px-4 py-2 text-slate-700 hover:bg-slate-100 transition-colors">
                                <i class="fas fa-history mr-3 w-5"></i> Pesanan Saya
                            </a>

                            <!-- Logout -->
                            <div class="border-t border-slate-100 mt-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-slate-700 hover:bg-slate-100 transition-colors">
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
                <button id="mobile-menu-button" class="text-slate-700 hover:text-emerald-700 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu (Hidden by default) -->
        <div id="mobile-menu" class="md:hidden hidden border-t border-slate-200/70 py-4">
            @guest
                <!-- Guest Mobile Menu -->
                <div class="space-y-3">
                    <a href="{{ route('home') }}" class="flex items-center text-slate-700 hover:text-emerald-700 py-2">
                        <i class="fas fa-home mr-3 w-6"></i> Home
                    </a>
                    <a href="{{ route('login') }}" class="flex items-center text-slate-700 hover:text-emerald-700 py-2">
                        <i class="fas fa-sign-in-alt mr-3 w-6"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center text-slate-700 hover:text-emerald-700 py-2">
                        <i class="fas fa-user-plus mr-3 w-6"></i> Daftar
                    </a>
                </div>
            @else
                <!-- Authenticated Mobile Menu -->
                <div class="space-y-3">
                    <!-- User Info -->
                    <div class="flex items-center space-x-3 rounded-xl bg-slate-50 p-2">
                        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-emerald-700"></i>
                        </div>
                        <div>
                            <div class="font-medium">{{ Auth::user()->name }}</div>
                            <div class="text-xs">
                                @php
                                    $roleBadge = [
                                        'admin' => 'text-slate-600 bg-slate-100',
                                        'cs_layer1' => 'text-cyan-700 bg-cyan-50',
                                        'cs_layer2' => 'text-emerald-700 bg-emerald-50',
                                        'customer' => 'text-amber-700 bg-amber-50'
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
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center text-slate-700 hover:bg-slate-100 py-2 px-3 rounded-lg">
                                <i class="fas fa-cog mr-3 w-5"></i> Admin Dashboard
                            </a>
                        @elseif(Auth::user()->isCSLayer1())
                            <a href="{{ route('cs1.dashboard') }}" class="flex items-center text-slate-700 hover:bg-slate-100 py-2 px-3 rounded-lg">
                                <i class="fas fa-headset mr-3 w-5"></i> CS Layer 1 Dashboard
                            </a>
                        @elseif(Auth::user()->isCSLayer2())
                            <a href="{{ route('cs2.dashboard') }}" class="flex items-center text-slate-700 hover:bg-slate-100 py-2 px-3 rounded-lg">
                                <i class="fas fa-shipping-fast mr-3 w-5"></i> CS Layer 2 Dashboard
                            </a>
                        @endif

                        <a href="{{ route('home') }}" class="flex items-center text-slate-700 hover:bg-slate-100 py-2 px-3 rounded-lg">
                            <i class="fas fa-home mr-3 w-5"></i> Home
                        </a>
                        
                        <a href="{{ route('cart.index') }}" class="flex items-center text-slate-700 hover:bg-slate-100 py-2 px-3 rounded-lg">
                            <i class="fas fa-shopping-cart mr-3 w-5"></i> Keranjang
                            @if($cartCount > 0)
                                <span class="ml-auto flex h-6 w-6 items-center justify-center rounded-full bg-amber-500 text-xs text-white">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                        
                        <a href="{{ route('my.orders.index') }}" class="flex items-center text-slate-700 hover:bg-slate-100 py-2 px-3 rounded-lg">
                            <i class="fas fa-history mr-3 w-5"></i> Pesanan Saya
                        </a>
                    </div>

                    <!-- Logout -->
                    <div class="border-t border-slate-200/70 pt-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full text-slate-700 hover:bg-slate-100 py-2 px-3 rounded-lg">
                                <i class="fas fa-sign-out-alt mr-3 w-5"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</nav>

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
