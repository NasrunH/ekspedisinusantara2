<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ekspedisi Nusantara')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e40af 0%, #0ea5e9 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                            <i class="fas fa-shipping-fast text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Ekspedisi Nusantara</h1>
                            <p class="text-xs text-primary-600">{{ env('DEVICE_NAME', 'Sistem Manajemen') }}</p>
                        </div>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('shipments.index') }}" 
                       class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('shipments.*') ? 'text-primary-600 bg-primary-50' : '' }}">
                        <i class="fas fa-list mr-2"></i>
                        Daftar Pengiriman
                    </a>
                    @if(request()->routeIs('shipments.create'))
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i>
                            Tambah Pengiriman
                        </span>
                    @else
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                            <i class="fas fa-database mr-1"></i>
                            {{ env('DB_CONNECTION') == 'mysql' ? 'MySQL' : 'PostgreSQL' }}
                        </span>
                    @endif
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-600 hover:text-gray-900" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-gray-50">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('shipments.index') }}" 
                   class="text-gray-600 hover:text-primary-600 block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-list mr-2"></i>
                    Daftar Pengiriman
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                    <span class="text-red-800">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-3 mt-1"></i>
                    <div>
                        <p class="text-red-800 font-medium mb-2">Terjadi kesalahan:</p>
                        <ul class="text-red-700 text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <p class="text-gray-600">
                    &copy; {{ date('Y') }} Ekspedisi Nusantara. Semua hak dilindungi.
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    Sistem Manajemen Ekspedisi Terintegrasi
                </p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('[class*="bg-green-"], [class*="bg-red-"]');
            alerts.forEach(alert => {
                if (alert.parentElement) {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
