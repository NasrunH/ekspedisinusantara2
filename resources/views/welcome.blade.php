@extends('layouts.app')

@section('title', 'Ekspedisi Nusantara - Sistem Manajemen')

@section('content')
<!-- Hero Section -->
<div class="text-center mb-16">
    <div class="inline-flex items-center justify-center w-20 h-20 gradient-bg rounded-2xl mb-6">
        <i class="fas fa-shipping-fast text-white text-3xl"></i>
    </div>
    <h1 class="text-5xl font-bold text-gray-900 mb-4">
        Ekspedisi <span class="text-primary-600">Nusantara</span>
    </h1>
    <p class="text-xl text-gray-600 mb-4 max-w-2xl mx-auto">
        Sistem Manajemen Ekspedisi Terintegrasi
    </p>
    <p class="text-gray-500 mb-8 max-w-2xl mx-auto">
        Solusi lengkap untuk mengelola pengiriman dengan teknologi database terdistribusi
    </p>
    
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('shipments.index') }}" 
           class="inline-flex items-center px-8 py-4 gradient-bg text-white font-semibold rounded-xl hover:opacity-90 transition-all duration-300 shadow-lg">
            <i class="fas fa-list mr-3"></i>
            Kelola Pengiriman
        </a>
        @if(Route::has('shipments.create'))
        <a href="{{ route('shipments.create') }}" 
           class="inline-flex items-center px-8 py-4 bg-white border-2 border-primary-600 text-primary-600 font-semibold rounded-xl hover:bg-primary-50 transition-all duration-300">
            <i class="fas fa-plus mr-3"></i>
            Tambah Pengiriman
        </a>
        @endif
    </div>
</div>

<!-- Stats Dashboard -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
    <div class="bg-white rounded-xl p-6 shadow-lg card-hover border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-amber-600 text-sm font-medium">Menunggu</p>
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Shipment::where('status', 'pending')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-amber-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-lg card-hover border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-600 text-sm font-medium">Dalam Perjalanan</p>
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Shipment::where('status', 'in_transit')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-shipping-fast text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-lg card-hover border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-600 text-sm font-medium">Terkirim</p>
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Shipment::where('status', 'delivered')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-lg card-hover border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-primary-600 text-sm font-medium">Total</p>
                <p class="text-3xl font-bold text-gray-900">{{ \App\Models\Shipment::count() }}</p>
            </div>
            <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-bar text-primary-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Track -->
<div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-200 mb-16">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">
        <i class="fas fa-search text-primary-600 mr-3"></i>
        Lacak Pengiriman
    </h2>
    <div class="max-w-md mx-auto">
        <form id="trackingForm" class="relative">
            <input type="text" id="trackingNumber" 
                   placeholder="Masukkan nomor resi..."
                   class="w-full px-6 py-4 bg-gray-50 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <button type="submit" 
                    class="absolute right-2 top-2 px-6 py-2 gradient-bg text-white rounded-lg hover:opacity-90 transition-all duration-300">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <div id="trackingResult" class="mt-4 hidden"></div>
    </div>
</div>

<!-- Features Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
    <div class="bg-white rounded-2xl p-8 shadow-lg card-hover border border-gray-200">
        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mb-6">
            <i class="fas fa-database text-primary-600 text-xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-3">Database Terdistribusi</h3>
        <p class="text-gray-600">
            Sistem menggunakan MySQL dan PostgreSQL untuk redundansi dan performa optimal
        </p>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-lg card-hover border border-gray-200">
        <div class="w-12 h-12 bg-secondary-100 rounded-xl flex items-center justify-center mb-6">
            <i class="fas fa-sync-alt text-secondary-600 text-xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-3">Sinkronisasi Real-time</h3>
        <p class="text-gray-600">
            Data tersinkronisasi secara otomatis antar sistem untuk konsistensi informasi
        </p>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-lg card-hover border border-gray-200">
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-6">
            <i class="fas fa-shield-alt text-green-600 text-xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-3">Keamanan Data</h3>
        <p class="text-gray-600">
            Backup otomatis dan redundansi data memastikan keamanan informasi pengiriman
        </p>
    </div>
</div>

<!-- System Info -->
<div class="bg-gradient-to-r from-primary-600 to-secondary-600 rounded-2xl p-8 text-white">
    <div class="text-center">
        <h2 class="text-2xl font-bold mb-4">Sistem Informasi</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white bg-opacity-20 rounded-xl p-4">
                <i class="fas fa-server text-2xl mb-2"></i>
                <p class="font-semibold">Database</p>
                <p class="text-sm opacity-90">{{ env('DB_CONNECTION') == 'mysql' ? 'MySQL Primary' : 'PostgreSQL Primary' }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-xl p-4">
                <i class="fas fa-desktop text-2xl mb-2"></i>
                <p class="font-semibold">Sistem</p>
                <p class="text-sm opacity-90">{{ env('DEVICE_NAME', 'Sistem Manajemen') }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-xl p-4">
                <i class="fas fa-clock text-2xl mb-2"></i>
                <p class="font-semibold">Status</p>
                <p class="text-sm opacity-90">Online & Tersinkronisasi</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('trackingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const trackingNumber = document.getElementById('trackingNumber').value;
    const resultDiv = document.getElementById('trackingResult');
    
    if (!trackingNumber) {
        return;
    }
    
    // Show loading
    resultDiv.innerHTML = `
        <div class="bg-white rounded-lg p-4 text-center border border-gray-200 shadow-sm">
            <i class="fas fa-spinner fa-spin text-primary-600 text-xl mb-2"></i>
            <p class="text-gray-600">Mencari pengiriman...</p>
        </div>
    `;
    resultDiv.classList.remove('hidden');
    
    // Fetch tracking data
    fetch(`/api/track?tracking_number=${encodeURIComponent(trackingNumber)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const shipment = data.data;
                let statusColor = 'amber';
                if (shipment.status === 'in_transit') statusColor = 'blue';
                if (shipment.status === 'delivered') statusColor = 'green';
                
                resultDiv.innerHTML = `
                    <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Hasil Pelacakan</h3>
                            <span class="px-3 py-1 bg-${statusColor}-100 text-${statusColor}-800 rounded-full text-sm font-medium">
                                ${shipment.status_icon} ${shipment.status_label}
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">No. Resi:</p>
                                <p class="text-gray-900 font-medium">${shipment.tracking_number}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Berat:</p>
                                <p class="text-gray-900 font-medium">${shipment.weight} kg</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Pengirim:</p>
                                <p class="text-gray-900 font-medium">${shipment.sender_name}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Penerima:</p>
                                <p class="text-gray-900 font-medium">${shipment.recipient_name}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Dibuat:</p>
                                <p class="text-gray-900 font-medium">${shipment.created_at}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Terakhir Update:</p>
                                <p class="text-gray-900 font-medium">${shipment.updated_at}</p>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                        <div class="flex items-center text-red-800">
                            <i class="fas fa-exclamation-circle mr-3"></i>
                            <span>${data.message}</span>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            resultDiv.innerHTML = `
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <div class="flex items-center text-red-800">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <span>Terjadi kesalahan saat melacak pengiriman.</span>
                    </div>
                </div>
            `;
        });
});
</script>
@endpush
@endsection
