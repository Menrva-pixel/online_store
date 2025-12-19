<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Online')</title>
    
    <!-- CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    @stack('styles')
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Meta Tags -->
    <meta name="description" content="Toko Online - Platform belanja online terpercaya">
    <meta name="keywords" content="toko online, belanja, ecommerce">
    <meta name="author" content="Toko Online">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navbar -->
    @include('partials.navbar')
    
    <!-- Flash Messages -->
    @include('partials.flash-messages')
    
    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('partials.footer')
    
    <!-- Scripts -->
    @stack('scripts')
    
    <!-- Mobile Menu Toggle Script -->
    <script>
        // Toggle mobile menu
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = document.querySelector('[aria-controls="mobile-menu"]');
            
            if (mobileMenu && !mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
        
        // Close flash messages after 5 seconds
        setTimeout(function() {
            const flashMessages = document.querySelectorAll('[role="alert"]');
            flashMessages.forEach(function(message) {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s';
                setTimeout(function() {
                    message.remove();
                }, 500);
            });
        }, 5000);
        
        // Handle user dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const userButton = document.querySelector('.group button');
            const userDropdown = document.querySelector('.group .absolute');
            
            if (userButton && userDropdown) {
                userButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function() {
                    userDropdown.classList.add('hidden');
                });
                
                // Prevent dropdown from closing when clicking inside
                userDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && e.key === 'B') {
                console.clear();
                console.log('%c' + `
        ██████╗  █████╗ ██████╗ ██╗  ██╗ █████╗ ██╗  ██╗
        ██╔══██╗██╔══██╗██╔══██╗██║ ██╔╝██╔══██╗██║  ██║
        ██████╔╝███████║██████╔╝█████╔╝ ███████║███████║
        ██╔══██╗██╔══██║██╔══██╗██╔═██╗ ██╔══██║██╔══██║
        ██████╔╝██║  ██║██║  ██║██║  ██╗██║  ██║██║  ██║
        ╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝
                `, 'color: #FF9800; font-family: monospace; font-size: 10px;');
                
                console.log('%c Developer: barkah herdyanto sejati', 
                    'color: #4CAF50; font-size: 18px; font-weight: bold;');
            }
});

// Always show minimal credit
console.log('%c👨‍💻 barkah herdyanto sejati', 
    'color: #607D8B; font-size: 11px;');

    </script>
</body>
</html>