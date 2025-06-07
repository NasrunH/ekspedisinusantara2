@extends('layouts.app')

@section('title', 'ExpressTrack - Device 2 Status Update Interface')

@section('content')
<!-- Hero Section -->
<div class="text-center mb-16">
    <div class="inline-flex items-center justify-center w-20 h-20 gradient-bg rounded-2xl mb-6">
        <i class="fas fa-edit text-white text-3xl"></i>
    </div>
    <h1 class="text-5xl font-bold text-white mb-4">
        Express<span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">Track</span>
    </h1>
    <p class="text-xl text-gray-300 mb-4 max-w-2xl mx-auto">
        Device 2 - Status Update Interface
    </p>
    <p class="text-gray-400 mb-8 max-w-2xl mx-auto">
        Interface khusus untuk mengupdate status pengiriman dengan PostgreSQL sebagai database utama
    </p>
    
    <!-- Device Info -->
    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
        <div class="glass rounded-xl p-4">
            <div class="flex items-center space-x-2 text-sm">
                <i class="fas fa-database text-purple-400"></i>
                <span class="text-white">PostgreSQL Primary</span>
            </div>
        </div>
        <div class="glass rounded-xl p-4">
            <div class="flex items-center space-x-2 text-sm">
                <i class="fas fa-edit text-blue-400"></i>
                <span class="text-white">Status Update Only</span>
            </div>
        </div>
        <div class="glass rounded-xl p-4">
            <div class="flex items-center space-x-2 text-sm">
                <i class="fas fa-sync-alt text-green-400"></i>
                <span class="text-white">Auto Sync to MySQL</span>
            </div>
        </div>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('shipments.index') }}" 
           class="inline-flex items-center px-8 py-4 gradient-bg text-white font-semibold rounded-xl hover:opacity-90 transition-all duration-300 transform hover:scale-105 shadow-lg">
            <i class="fas fa-list mr-3"></i>
            Lihat Status Pengiriman
        </a>
        <div class="glass rounded-xl px-6 py-3">
            <span class="text-gray-300 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                Untuk membuat pengiriman baru, gunakan Device 1
            </span>
        </div>
    </div>
</div>

<!-- Features Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
    <div class="glass rounded-2xl p-8 card-hover">
        <div class="w-12 h-12 bg-purple-500 bg-opacity-20 rounded-xl flex items-center justify-center mb-6">
            <i class="fas fa-database text-purple-400 text-xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-white mb-3">PostgreSQL Primary</h3>
        <p class="text-gray-400">
            Menggunakan PostgreSQL sebagai database utama untuk Device 2 dengan sinkronisasi ke MySQL
        </p>
    </div>

    <div class="glass rounded-2xl p-8 card-hover">
        <div class="w-12 h-12 bg-blue-500 bg-opacity-20 rounded-xl flex items-center justify-center mb-6">
            <i class="fas fa-edit text-blue-400 text-xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-white mb-3">Status Update</h3>
        <p class="text-gray-400">
            Interface khusus untuk mengupdate status pengiriman dengan antarmuka yang intuitif
        </p>
    </div>

    <div class="glass rounded-2xl p-8 card-hover">
        <div class="w-12 h-12 bg-green-500 bg-opacity-20 rounded-xl flex items-center justify-center mb-6">
            <i class="fas fa-sync-alt text-green-400 text-xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-white mb-3">Auto Sync</h3>
        <p class="text-gray-400">
            Setiap perubahan status otomatis tersinkronisasi ke MySQL (Device 1)
        </p>
    </div>
</div>

<!-- Quick Track -->
<div class="glass rounded-2xl p-8 mb-16">
    <h2 class="text-2xl font-bold text-white mb-6 text-center">
        <i class="fas fa-search text-purple-400 mr-3"></i>
        Lacak Status Pengiriman
    </h2>
    <div class="max-w-md mx-auto">
        <form id="trackingForm" class="relative">
            <input type="text" id="trackingNumber" 
                   placeholder="Masukkan nomor resi..."
                   class="w-full px-6 py-4 bg-gray-800 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            <button type="submit" 
                    class="absolute right-2 top-2 px-6 py-2 gradient-bg text-white rounded-lg hover:opacity-90 transition-all duration-300">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <div id="trackingResult" class="mt-4 hidden"></div>
    </div>
</div>

<!-- Stats Dashboard -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
    <div class="glass rounded-2xl p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-400 text-sm font-medium">Menunggu</p>
                <p class="text-3xl font-bold text-white">{{ \App\Models\Shipment::where('status', 'pending')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-500 bg-opacity-20 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-yellow-400 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="glass rounded-2xl p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-400 text-sm font-medium">Dalam Perjalanan</p>
                <p class="text-3xl font-bold text-white">{{ \App\Models\Shipment::where('status', 'in_transit')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-500 bg-opacity-20 rounded-xl flex items-center justify-center">
                <i class="fas fa-shipping-fast text-blue-400 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="glass rounded-2xl p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-400 text-sm font-medium">Terkirim</p>
                <p class="text-3xl font-bold text-white">{{ \App\Models\Shipment::where('status', 'delivered')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-500 bg-opacity-20 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-green-400 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="glass rounded-2xl p-6 card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-400 text-sm font-medium">Total</p>
                <p class="text-3xl font-bold text-white">{{ \App\Models\Shipment::count() }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-500 bg-opacity-20 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-bar text-purple-400 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Device Comparison -->
<div class="glass rounded-2xl p-8">
    <h2 class="text-2xl font-bold text-white mb-6 text-center">
        <i class="fas fa-info-circle text-blue-400 mr-3"></i>
        Perbedaan Device 1 vs Device 2
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-800 bg-opacity-50 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <span class="w-8 h-8 bg-blue-500 bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-blue-400 font-bold">1</span>
                </span>
                Device 1 - Full CRUD
            </h3>
            <ul class="space-y-2 text-gray-300 text-sm">
                <li class="flex items-center"><i class="fas fa-database text-blue-400 mr-2"></i> MySQL Primary</li>
                <li class="flex items-center"><i class="fas fa-plus text-green-400 mr-2"></i> Create Pengiriman</li>
                <li class="flex items-center"><i class="fas fa-edit text-yellow-400 mr-2"></i> Edit Semua Data</li>
                <li class="flex items-center"><i class="fas fa-trash text-red-400 mr-2"></i> Delete Pengiriman</li>
                <li class="flex items-center"><i class="fas fa-eye text-purple-400 mr-2"></i> View Detail</li>
                <li class="flex items-center"><i class="fas fa-desktop text-gray-400 mr-2"></i> Port 8000</li>
            </ul>
        </div>
        
        <div class="bg-purple-800 bg-opacity-30 rounded-xl p-6 border border-purple-500 border-opacity-30">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                <span class="w-8 h-8 bg-purple-500 bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-purple-400 font-bold">2</span>
                </span>
                Device 2 - Status Update
            </h3>
            <ul class="space-y-2 text-gray-300 text-sm">
                <li class="flex items-center"><i class="fas fa-database text-purple-400 mr-2"></i> PostgreSQL Primary</li>
                <li class="flex items-center"><i class="fas fa-edit text-blue-400 mr-2"></i> Update Status Only</li>
                <li class="flex items-center"><i class="fas fa-eye text-green-400 mr-2"></i> View Detail</li>
                <li class="flex items-center"><i class="fas fa-sync-alt text-yellow-400 mr-2"></i> Auto Sync to MySQL</li>
                <li class="flex items-center"><i class="fas fa-moon text-purple-400 mr-2"></i> Dark Theme</li>
                <li class="flex items-center"><i class="fas fa-desktop text-gray-400 mr-2"></i> Port 8001</li>
            </ul>
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
        <div class="glass rounded-lg p-4 text-center">
            <i class="fas fa-spinner fa-spin text-purple-400 text-xl mb-2"></i>
            <p class="text-gray-300">Mencari pengiriman...</p>
        </div>
    `;
    resultDiv.classList.remove('hidden');
    
    // Fetch tracking data
    fetch(`/api/track?tracking_number=${encodeURIComponent(trackingNumber)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const shipment = data.data;
                let statusColor = 'yellow';
                if (shipment.status === 'in_transit') statusColor = 'blue';
                if (shipment.status === 'delivered') statusColor = 'green';
                
                resultDiv.innerHTML = `
                    <div class="glass rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-white">Hasil Pelacakan</h3>
                            <span class="px-3 py-1 bg-${statusColor}-500 bg-opacity-20 text-${statusColor}-400 rounded-full text-sm font-medium border border-${statusColor}-500 border-opacity-30">
                                ${shipment.status_icon} ${shipment.status_label}
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-400">No. Resi:</p>
                                <p class="text-white font-medium">${shipment.tracking_number}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Berat:</p>
                                <p class="text-white font-medium">${shipment.weight} kg</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Pengirim:</p>
                                <p class="text-white font-medium">${shipment.sender_name}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Penerima:</p>
                                <p class="text-white font-medium">${shipment.recipient_name}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Dibuat:</p>
                                <p class="text-white font-medium">${shipment.created_at}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Terakhir Update:</p>
                                <p class="text-white font-medium">${shipment.updated_at}</p>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <span class="text-xs text-gray-400">
                                <i class="fas fa-database mr-1"></i>
                                Data dari PostgreSQL (Device 2)
                            </span>
                        </div>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="glass rounded-lg p-4 border border-red-500 border-opacity-30">
                        <div class="flex items-center text-red-400">
                            <i class="fas fa-exclamation-circle mr-3"></i>
                            <span>${data.message}</span>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            resultDiv.innerHTML = `
                <div class="glass rounded-lg p-4 border border-red-500 border-opacity-30">
                    <div class="flex items-center text-red-400">
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
