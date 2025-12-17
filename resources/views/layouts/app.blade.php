<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Online')</title>
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
                    <a href="/" class="text-xl font-bold text-blue-600">
                        <i class="fas fa-store mr-2"></i>Toko Online
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    @else
                        <span class="text-gray-700">Halo, {{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-blue-600 ml-4">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-lg mt-8">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <p class="text-center text-gray-600">
                &copy; {{ date('Y') }} Toko Online Sederhana. All rights reserved.
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>