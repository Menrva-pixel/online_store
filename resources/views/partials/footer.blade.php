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
                    @guest
                    <li><a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600">Login</a></li>
                    <li><a href="{{ route('register') }}" class="text-gray-600 hover:text-blue-600">Register</a></li>
                    @else
                    <li><a href="{{ route('my.orders.index') }}" class="text-gray-600 hover:text-blue-600">Pesanan Saya</a></li>
                    <li><a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-blue-600">Keranjang</a></li>
                    @endguest
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
                    <li class="flex items-center">
                        <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                        <span class="text-gray-600">Jakarta, Indonesia</span>
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