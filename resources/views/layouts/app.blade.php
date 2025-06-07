<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ExpressTrack - Device 2')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .dark .glass {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen">
    <!-- Navigation -->
    <nav class="glass border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                            <i class="fas fa-shipping-fast text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-white">ExpressTrack</h1>
                            <p class="text-xs text-purple-300">{{ env('DEVICE_NAME', 'Device 2') }}</p>
                        </div>
                    </a>
                </div>
                
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('shipments.index') }}" 
                       class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('shipments.*') ? 'text-purple-400' : '' }}">
                        <i class="fas fa-list mr-2"></i>
                        Daftar Pengiriman
                    </a>
                    <div class="glass rounded-lg px-4 py-2">
                        <span class="text-gray-300 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            Status Update Only
                        </span>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-300 hover:text-white" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-700">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('shipments.index') }}" 
                   class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-list mr-2"></i>
                    Daftar Pengiriman
                </a>
                <div class="text-gray-400 px-3 py-2 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Device 2 - Status Update Only
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-600 bg-opacity-20 border border-green-500 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-400 mr-3"></i>
                    <span class="text-green-100">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-600 bg-opacity-20 border border-red-500 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                    <span class="text-red-100">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-600 bg-opacity-20 border border-red-500 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-400 mr-3 mt-1"></i>
                    <div>
                        <p class="text-red-100 font-medium mb-2">Terjadi kesalahan:</p>
                        <ul class="text-red-200 text-sm space-y-1">
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
    <footer class="border-t border-gray-700 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <p class="text-gray-400">
                    &copy; {{ date('Y') }} ExpressTrack - Device 2 Modern Interface
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    Sistem Ekspedisi dengan Sinkronisasi Database MySQL & PostgreSQL
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
