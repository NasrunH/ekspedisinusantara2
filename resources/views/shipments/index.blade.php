@extends('layouts.app')

@section('title', 'Daftar Pengiriman - Ekspedisi Nusantara')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Daftar Pengiriman</h1>
        <p class="text-gray-600">Kelola semua pengiriman dalam sistem</p>
    </div>
    <div class="mt-4 md:mt-0 flex items-center space-x-3">
        @if(Route::has('shipments.create'))
        <a href="{{ route('shipments.create') }}" 
           class="inline-flex items-center px-6 py-3 gradient-bg text-white font-semibold rounded-lg hover:opacity-90 transition-all duration-300 shadow-lg">
            <i class="fas fa-plus mr-2"></i>
            Tambah Pengiriman
        </a>
        @endif
        <button onclick="syncDatabases()" id="syncButton" 
                class="inline-flex items-center px-6 py-3 bg-secondary-600 hover:bg-secondary-700 text-white font-semibold rounded-lg transition-all duration-300">
            <i class="fas fa-sync-alt mr-2"></i>
            <span id="syncText">Sinkronisasi</span>
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-amber-600 text-sm font-medium">Menunggu</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
            </div>
            <i class="fas fa-clock text-amber-600 text-xl"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-600 text-sm font-medium">Dalam Perjalanan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['in_transit'] }}</p>
            </div>
            <i class="fas fa-shipping-fast text-blue-600 text-xl"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-600 text-sm font-medium">Terkirim</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['delivered'] }}</p>
            </div>
            <i class="fas fa-check-circle text-green-600 text-xl"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-primary-600 text-sm font-medium">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
            <i class="fas fa-chart-bar text-primary-600 text-xl"></i>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mb-8">
    <form method="GET" action="{{ route('shipments.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari berdasarkan resi, pengirim, atau penerima..."
                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>
        </div>
        <div class="flex gap-3">
            <select name="status" class="px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Semua Status</option>
                @foreach(\App\Models\Shipment::getStatusOptions() as $value => $label)
                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-6 py-3 gradient-bg text-white rounded-lg hover:opacity-90 transition-all duration-300">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </button>
        </div>
    </form>
</div>

<!-- Shipments Grid -->
@if($shipments->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
        @foreach($shipments as $shipment)
            @if($shipment->id !== 0)
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 card-hover">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 gradient-bg rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-semibold">{{ $shipment->tracking_number }}</h3>
                                <p class="text-gray-500 text-sm">{{ $shipment->formatted_created_at }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-{{ $shipment->status_color }}-100 text-{{ $shipment->status_color }}-800 rounded-full text-xs font-medium">
                            {{ $shipment->status_icon }} {{ $shipment->status_label }}
                        </span>
                    </div>

                    <!-- Content -->
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-gray-900 text-sm font-medium">{{ $shipment->sender_name }}</p>
                                <p class="text-gray-500 text-xs">Pengirim</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-gray-900 text-sm font-medium">{{ $shipment->recipient_name }}</p>
                                <p class="text-gray-500 text-xs">Penerima</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-weight-hanging text-primary-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-gray-900 text-sm font-medium">{{ $shipment->weight_kg }} kg</p>
                                <p class="text-gray-500 text-xs">Berat</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route('shipments.show', $shipment) }}" 
                           class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-center rounded-lg transition-colors text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>
                            Detail
                        </a>
                        <a href="{{ route('shipments.edit', $shipment) }}" 
                           class="flex-1 px-4 py-2 gradient-bg hover:opacity-90 text-white text-center rounded-lg transition-all text-sm font-medium">
                            <i class="fas fa-edit mr-1"></i>
                            Edit
                        </a>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $shipments->appends(request()->query())->links() }}
    </div>
@else
    <div class="text-center py-16">
        <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-inbox text-gray-400 text-4xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum ada pengiriman</h3>
        <p class="text-gray-600 mb-6">Mulai dengan menambahkan pengiriman pertama</p>
        @if(Route::has('shipments.create'))
        <a href="{{ route('shipments.create') }}" 
           class="inline-flex items-center px-6 py-3 gradient-bg text-white font-semibold rounded-xl hover:opacity-90 transition-all duration-300">
            <i class="fas fa-plus mr-2"></i>
            Tambah Pengiriman Pertama
        </a>
        @endif
    </div>
@endif

<!-- Sync Result Modal -->
<div id="syncModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Hasil Sinkronisasi</h3>
        <div id="syncResult" class="text-gray-600">
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
    syncText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sinkronisasi...';
    
    // Show loading in modal
    result.innerHTML = `
        <div class="text-center">
            <i class="fas fa-spinner fa-spin text-primary-600 text-2xl mb-4"></i>
            <p>Melakukan sinkronisasi database...</p>
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
            result.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-check-circle text-green-600 text-3xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Sinkronisasi Berhasil</h4>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p>Data tersinkronisasi: <span class="font-medium">${data.synced_records}</span></p>
                        <p>Total data: <span class="font-medium">${data.total_records}</span></p>
                        <p>Waktu: <span class="font-medium">${data.timestamp}</span></p>
                    </div>
                </div>
            `;
            
            // Refresh page after successful sync
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            result.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-exclamation-circle text-red-600 text-3xl mb-4"></i>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Sinkronisasi Gagal</h4>
                    <p class="text-red-600">${data.message}</p>
                </div>
            `;
        }
    })
    .catch(error => {
        result.innerHTML = `
            <div class="text-center">
                <i class="fas fa-exclamation-circle text-red-600 text-3xl mb-4"></i>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Terjadi Kesalahan</h4>
                <p class="text-red-600">Error: ${error.message || 'Unknown error'}</p>
            </div>
        `;
    })
    .finally(() => {
        syncText.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Sinkronisasi';
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

