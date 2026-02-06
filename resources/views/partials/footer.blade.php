<footer class="mt-16 border-t border-slate-200/70 bg-slate-900 text-slate-200">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="flex items-center text-lg font-bold text-white mb-4">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white mr-2">
                        <i class="fas fa-store text-sm"></i>
                    </span>
                    Toko Online
                </h3>
                <p class="text-slate-300">
                    Platform belanja online terpercaya dengan kurasi produk berkualitas, pelayanan ramah,
                    dan pengiriman cepat.
                </p>
                <div class="mt-6 flex items-center gap-3 text-sm text-slate-400">
                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-800 px-3 py-1">
                        <i class="fas fa-shield-alt text-emerald-400"></i> Transaksi aman
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-800 px-3 py-1">
                        <i class="fas fa-truck text-amber-300"></i> Pengiriman cepat
                    </span>
                </div>
            </div>
            
            <div>
                <h3 class="font-bold text-lg text-white mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-slate-300 hover:text-white">Home</a></li>
                    @guest
                    <li><a href="{{ route('login') }}" class="text-slate-300 hover:text-white">Login</a></li>
                    <li><a href="{{ route('register') }}" class="text-slate-300 hover:text-white">Register</a></li>
                    @else
                    <li><a href="{{ route('my.orders.index') }}" class="text-slate-300 hover:text-white">Pesanan Saya</a></li>
                    <li><a href="{{ route('cart.index') }}" class="text-slate-300 hover:text-white">Keranjang</a></li>
                    @endguest
                </ul>
            </div>
            
            <div>
                <h3 class="font-bold text-lg text-white mb-4">Kontak</h3>
                <ul class="space-y-2">
                    <li class="flex items-center">
                        <i class="fas fa-envelope text-slate-400 mr-2"></i>
                        <span class="text-slate-300">support@tokoonline.com</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone text-slate-400 mr-2"></i>
                        <span class="text-slate-300">+62 812 3456 7890</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-map-marker-alt text-slate-400 mr-2"></i>
                        <span class="text-slate-300">Jakarta, Indonesia</span>
                    </li>
                </ul>
                <div class="mt-6 rounded-xl border border-slate-700 bg-slate-800/60 p-4 text-sm text-slate-300">
                    <div class="font-semibold text-white mb-1">Jam Layanan</div>
                    <div>Senin - Sabtu: 09.00 - 21.00</div>
                    <div>Minggu: 10.00 - 18.00</div>
                </div>
            </div>
        </div>
        
        <div class="border-t border-slate-800 mt-8 pt-6 text-center text-slate-400">
            <p>&copy; {{ date('Y') }} Toko Online Sederhana. All rights reserved.</p>
        </div>
    </div>
</footer>
