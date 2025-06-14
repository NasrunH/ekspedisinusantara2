@extends('layouts.app')

@section('title', 'Detail Pengiriman - Ekspedisi Nusantara')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
    <div class="flex items-center space-x-4">
        <a href="{{ route('shipments.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $shipment->tracking_number }}</h1>
            <p class="text-gray-600">Detail Pengiriman</p>
        </div>
    </div>
    <div class="mt-4 md:mt-0 flex items-center space-x-3">
        <a href="{{ route('shipments.edit', $shipment) }}" 
           class="inline-flex items-center px-6 py-3 gradient-bg text-white font-semibold rounded-lg hover:opacity-90 transition-all duration-300 shadow-lg">
            <i class="fas fa-edit mr-2"></i>
            Edit Pengiriman
        </a>
        <button onclick="printShipment()" 
                class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all duration-300">
            <i class="fas fa-print mr-2"></i>
            Cetak
        </button>
    </div>
</div>

<!-- Status Badge -->
<div class="flex justify-center mb-8">
    <div class="inline-flex items-center px-6 py-3 bg-{{ $shipment->status_color }}-100 text-{{ $shipment->status_color }}-800 rounded-2xl border border-{{ $shipment->status_color }}-200">
        <span class="text-2xl mr-3">{{ $shipment->status_icon }}</span>
        <div>
            <p class="font-semibold text-lg">{{ $shipment->status_label }}</p>
            <p class="text-sm opacity-75">Terakhir diupdate: {{ $shipment->formatted_updated_at }}</p>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Left Column - Shipment Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Package Information -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-box text-primary-600 text-xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Informasi Paket</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nomor Resi</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $shipment->tracking_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Berat</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $shipment->weight_kg }} kg</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <span class="inline-flex items-center px-3 py-1 bg-{{ $shipment->status_color }}-100 text-{{ $shipment->status_color }}-800 rounded-full text-sm font-medium">
                            {{ $shipment->status_icon }} {{ $shipment->status_label }}
                        </span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Dibuat</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $shipment->formatted_created_at }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Terakhir Diupdate</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $shipment->formatted_updated_at }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">ID Pengiriman</label>
                        <p class="text-lg font-semibold text-gray-900">#{{ $shipment->id }}</p>
                    </div>
                </div>
            </div>

            @if($shipment->description)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <label class="block text-sm font-medium text-gray-500 mb-2">Deskripsi Barang</label>
                <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $shipment->description }}</p>
            </div>
            @endif
        </div>

        <!-- Sender Information -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-user text-blue-600 text-xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Informasi Pengirim</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nama Pengirim</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $shipment->sender_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nomor Telepon</label>
                    <p class="text-lg font-semibold text-gray-900">
                        <a href="tel:{{ $shipment->sender_phone }}" class="text-primary-600 hover:text-primary-700">
                            {{ $shipment->sender_phone }}
                        </a>
                    </p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Pengirim</label>
                    <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $shipment->sender_address }}</p>
                </div>
            </div>
        </div>

        <!-- Recipient Information -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Informasi Penerima</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nama Penerima</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $shipment->recipient_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nomor Telepon</label>
                    <p class="text-lg font-semibold text-gray-900">
                        <a href="tel:{{ $shipment->recipient_phone }}" class="text-primary-600 hover:text-primary-700">
                            {{ $shipment->recipient_phone }}
                        </a>
                    </p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Penerima</label>
                    <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $shipment->recipient_address }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Timeline & Actions -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('shipments.edit', $shipment) }}" 
                   class="w-full inline-flex items-center justify-center px-4 py-3 gradient-bg text-white font-medium rounded-lg hover:opacity-90 transition-all">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Pengiriman
                </a>
                <button onclick="copyTrackingNumber()" 
                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-secondary-600 hover:bg-secondary-700 text-white font-medium rounded-lg transition-all">
                    <i class="fas fa-copy mr-2"></i>
                    Salin Nomor Resi
                </button>
                <button onclick="shareShipment()" 
                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all">
                    <i class="fas fa-share mr-2"></i>
                    Bagikan
                </button>
                <button onclick="printShipment()" 
                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all">
                    <i class="fas fa-print mr-2"></i>
                    Cetak Detail
                </button>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-history text-orange-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Timeline Pengiriman</h3>
            </div>

            <div class="space-y-6">
                <!-- Created -->
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900">Pengiriman Dibuat</h4>
                        <p class="text-sm text-gray-600">{{ $shipment->formatted_created_at }}</p>
                        <p class="text-xs text-gray-500 mt-1">Paket telah terdaftar dalam sistem</p>
                    </div>
                </div>

                <!-- In Transit -->
                @if($shipment->status == 'in_transit' || $shipment->status == 'delivered')
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shipping-fast text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900">Dalam Perjalanan</h4>
                        <p class="text-sm text-gray-600">{{ $shipment->formatted_updated_at }}</p>
                        <p class="text-xs text-gray-500 mt-1">Paket sedang dalam perjalanan ke tujuan</p>
                    </div>
                </div>
                @endif

                <!-- Delivered -->
                @if($shipment->status == 'delivered')
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check-circle text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900">Terkirim</h4>
                        <p class="text-sm text-gray-600">{{ $shipment->formatted_updated_at }}</p>
                        <p class="text-xs text-gray-500 mt-1">Paket telah berhasil diterima oleh penerima</p>
                    </div>
                </div>
                @endif

                <!-- Pending Status -->
                @if($shipment->status == 'pending')
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-clock text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900">Menunggu Proses</h4>
                        <p class="text-sm text-gray-600">{{ $shipment->formatted_updated_at }}</p>
                        <p class="text-xs text-gray-500 mt-1">Paket sedang menunggu untuk diproses</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- System Information -->
        <div class="bg-gradient-to-r from-primary-600 to-secondary-600 rounded-xl p-6 text-white">
            <h3 class="text-lg font-bold mb-4">Informasi Sistem</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="opacity-90">Database:</span>
                    <span class="font-medium">{{ env('DB_CONNECTION') == 'mysql' ? 'MySQL' : 'PostgreSQL' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="opacity-90">Device:</span>
                    <span class="font-medium">{{ env('DEVICE_NAME', 'Sistem Utama') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="opacity-90">Status Sync:</span>
                    <span class="font-medium flex items-center">
                        <i class="fas fa-check-circle mr-1"></i>
                        Tersinkronisasi
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Shipments -->
<div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Pengiriman Terkait</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @php
            $relatedShipments = \App\Models\Shipment::where('id', '!=', $shipment->id)
                ->where(function($query) use ($shipment) {
                    $query->where('sender_name', 'like', '%' . explode(' ', $shipment->sender_name)[0] . '%')
                          ->orWhere('recipient_name', 'like', '%' . explode(' ', $shipment->recipient_name)[0] . '%');
                })
                ->limit(3)
                ->get();
        @endphp

        @forelse($relatedShipments as $related)
        <a href="{{ route('shipments.show', $related) }}" 
           class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
            <div class="flex items-center justify-between mb-2">
                <span class="font-medium text-gray-900">{{ $related->tracking_number }}</span>
                <span class="px-2 py-1 bg-{{ $related->status_color }}-100 text-{{ $related->status_color }}-800 rounded text-xs">
                    {{ $related->status_label }}
                </span>
            </div>
            <p class="text-sm text-gray-600">{{ $related->sender_name }} → {{ $related->recipient_name }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $related->formatted_created_at }}</p>
        </a>
        @empty
        <div class="col-span-3 text-center py-8 text-gray-500">
            <i class="fas fa-inbox text-2xl mb-2"></i>
            <p>Tidak ada pengiriman terkait</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyTrackingNumber() {
    const trackingNumber = '{{ $shipment->tracking_number }}';
    navigator.clipboard.writeText(trackingNumber).then(function() {
        // Show success message
        showNotification('Nomor resi berhasil disalin!', 'success');
    }, function(err) {
        console.error('Could not copy text: ', err);
        showNotification('Gagal menyalin nomor resi', 'error');
    });
}

function shareShipment() {
    const url = window.location.href;
    const text = `Lacak pengiriman {{ $shipment->tracking_number }} di Ekspedisi Nusantara: ${url}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'Lacak Pengiriman - {{ $shipment->tracking_number }}',
            text: text,
            url: url
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(text).then(function() {
            showNotification('Link berhasil disalin!', 'success');
        });
    }
}

function printShipment() {
    window.print();
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Print styles
const printStyles = `
    <style>
        @media print {
            body * { visibility: hidden; }
            .print-area, .print-area * { visibility: visible; }
            .print-area { position: absolute; left: 0; top: 0; width: 100%; }
            nav, footer, .no-print { display: none !important; }
        }
    </style>
`;
document.head.insertAdjacentHTML('beforeend', printStyles);

// Add print-area class to main content
document.querySelector('main').classList.add('print-area');
</script>
@endpush

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    .print-only {
        display: block !important;
    }
    
    body {
        font-size: 12pt;
        line-height: 1.4;
    }
    
    .bg-gradient-to-r {
        background: #1e40af !important;
        color: white !important;
    }
}
</style>
