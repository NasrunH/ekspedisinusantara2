@extends('layouts.app')

@section('title', 'Daftar Pengiriman - ExpressTrack Device 2')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
    <div>
        <h1 class="text-4xl font-bold text-white mb-2">Status Pengiriman</h1>
        <p class="text-gray-400">Device 2 - Update Status Pengiriman</p>
        <div class="flex items-center space-x-2 mt-2 text-sm">
            <span class="px-3 py-1 bg-purple-600 bg-opacity-20 text-purple-400 rounded-full border border-purple-500 border-opacity-30">
                <i class="fas fa-database mr-1"></i>
                PostgreSQL Primary
            </span>
            <span class="px-3 py-1 bg-blue-600 bg-opacity-20 text-blue-400 rounded-full border border-blue-500 border-opacity-30">
                <i class="fas fa-edit mr-1"></i>
                Status Update Only
            </span>
        </div>
    </div>
    <div class="mt-4 md:mt-0">
        <div class="glass rounded-xl p-4">
            <p class="text-gray-300 text-sm text-center">
                <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                Untuk membuat pengiriman baru,<br>gunakan Device 1
            </p>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="glass rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-400 text-sm">Menunggu</p>
                <p class="text-2xl font-bold text-white">{{ $stats['pending'] }}</p>
            </div>
            <i class="fas fa-clock text-yellow-400 text-xl"></i>
        </div>
    </div>
    <div class="glass rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-400 text-sm">Dalam Perjalanan</p>
                <p class="text-2xl font-bold text-white">{{ $stats['in_transit'] }}</p>
            </div>
            <i class="fas fa-shipping-fast text-blue-400 text-xl"></i>
        </div>
    </div>
    <div class="glass rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-400 text-sm">Terkirim</p>
                <p class="text-2xl font-bold text-white">{{ $stats['delivered'] }}</p>
            </div>
            <i class="fas fa-check-circle text-green-400 text-xl"></i>
        </div>
    </div>
    <div class="glass rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-400 text-sm">Total</p>
                <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
            </div>
            <i class="fas fa-chart-bar text-purple-400 text-xl"></i>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="glass rounded-2xl p-6 mb-8">
    <form method="GET" action="{{ route('shipments.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari berdasarkan resi, pengirim, atau penerima..."
                       class="w-full pl-12 pr-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
        </div>
        <div class="flex gap-3">
            <select name="status" class="px-4 py-3 bg-gray-800 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">Semua Status</option>
                @foreach(\App\Models\Shipment::getStatusOptions() as $value => $label)
                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl transition-colors">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </button>
            <button type="button" onclick="syncDatabases()" id="syncButton" 
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>
                <span id="syncText">Sync</span>
            </button>
        </div>
    </form>
</div>

<!-- Shipments Grid -->
@if($shipments->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
        @foreach($shipments as $shipment)
            <div class="glass rounded-2xl p-6 card-hover">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold">{{ $shipment->tracking_number }}</h3>
                            <p class="text-gray-400 text-sm">{{ $shipment->formatted_created_at }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-{{ $shipment->status_color }}-500 bg-opacity-20 text-{{ $shipment->status_color }}-400 rounded-full text-xs font-medium border border-{{ $shipment->status_color }}-500 border-opacity-30">
                        {{ $shipment->status_icon }} {{ $shipment->status_label }}
                    </span>
                </div>

                <!-- Content -->
                <div class="space-y-3 mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-blue-400 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-white text-sm font-medium">{{ $shipment->sender_name }}</p>
                            <p class="text-gray-400 text-xs">Pengirim</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-green-400 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-white text-sm font-medium">{{ $shipment->recipient_name }}</p>
                            <p class="text-gray-400 text-xs">Penerima</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-weight-hanging text-purple-400 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-white text-sm font-medium">{{ $shipment->weight_kg }} kg</p>
                            <p class="text-gray-400 text-xs">Berat</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-2">
                    <a href="{{ route('shipments.show', $shipment) }}" 
                       class="flex-1 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-center rounded-lg transition-colors text-sm font-medium">
                        <i class="fas fa-eye mr-1"></i>
                        Detail
                    </a>
                    <a href="{{ route('shipments.edit', $shipment) }}" 
                       class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-center rounded-lg transition-colors text-sm font-medium">
                        <i class="fas fa-edit mr-1"></i>
                        Update Status
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $shipments->appends(request()->query())->links() }}
    </div>
@else
    <div class="text-center py-16">
        <div class="w-24 h-24 glass rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-inbox text-gray-400 text-4xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-white mb-2">Belum ada pengiriman</h3>
        <p class="text-gray-400 mb-6">Sinkronisasi data dari Device 1 untuk melihat pengiriman</p>
        <button onclick="syncDatabases()" 
                class="inline-flex items-center px-6 py-3 gradient-bg text-white font-semibold rounded-xl hover:opacity-90 transition-all duration-300">
            <i class="fas fa-sync-alt mr-2"></i>
            Sinkronisasi Data
        </button>
    </div>
@endif

<!-- Sync Result Modal -->
<div id="syncModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="glass rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-white mb-4">Hasil Sinkronisasi</h3>
        <div id="syncResult" class="text-gray-300">
            Sedang melakukan sinkronisasi...
        </div>
        <button onclick="closeSyncModal()" class="mt-6 w-full px-4 py-2 gradient-bg text-white rounded-lg hover:opacity-90 transition-all">
            Tutup
        </button>
    </div>
</div>

@push('scripts')
<script>
function syncDatabases() {
    const button = document.getElementById('syncButton');
    const syncText = document.getElementById('syncText');
    const modal = document.getElementById('syncModal');
    const result = document.getElementById('syncResult');
    
    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Update button
    button.disabled = true;
    syncText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Syncing...';
    
    // Show loading in modal
    result.innerHTML = `
        <div class="text-center">
            <i class="fas fa-spinner fa-spin text-purple-400 text-2xl mb-4"></i>
            <p>Menarik data dari MySQL ke PostgreSQL...</p>
            <p class="text-sm text-gray-400 mt-2">Device 2 - Status Update Interface</p>
        </div>
    `;
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    fetch('/api/sync-databases', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken || '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let warningsHtml = '';
            if (data.warnings && data.warnings.length > 0) {
                warningsHtml = `
                    <div class="mt-4 p-3 bg-yellow-600 bg-opacity-20 border border-yellow-500 border-opacity-30 rounded-lg">
                        <p class="text-yellow-400 font-medium mb-2">Peringatan:</p>
                        <ul class="text-yellow-300 text-sm space-y-1">
                            ${data.warnings.map(warning => `<li>• ${warning}</li>`).join('')}
                        </ul>
                    </div>
                `;
            }
            
            result.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-check-circle text-green-400 text-3xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-white mb-2">${data.message}</h4>
                    <div class="text-sm text-gray-300 space-y-1">
                        <p>Data tersinkronisasi: <span class="text-white font-medium">${data.synced_records}</span></p>
                        <p>Total data: <span class="text-white font-medium">${data.total_records}</span></p>
                        <p>Arah sync: <span class="text-blue-400 font-medium">${data.sync_direction}</span></p>
                        <p>Device: <span class="text-purple-400 font-medium">${data.device}</span></p>
                        <p>Waktu: <span class="text-white font-medium">${data.timestamp}</span></p>
                    </div>
                    ${warningsHtml}
                </div>
            `;
            
            // Refresh page after successful sync
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            result.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-exclamation-circle text-red-400 text-3xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-white mb-2">Sinkronisasi Gagal</h4>
                    <p class="text-red-300">${data.message}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        result.innerHTML = `
            <div class="text-center">
                <i class="fas fa-exclamation-circle text-red-400 text-3xl mb-4"></i>
                <h4 class="text-lg font-semibold text-white mb-2">Terjadi Kesalahan</h4>
                <p class="text-red-300">Error: ${error.message || 'Unknown error'}</p>
            </div>
        `;
    })
    .finally(() => {
        syncText.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Sync';
        button.disabled = false;
    });
}

function closeSyncModal() {
    const modal = document.getElementById('syncModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('syncModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSyncModal();
    }
});
</script>
@endpush
@endsection
