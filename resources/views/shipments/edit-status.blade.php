@extends('layouts.app')

@section('title', 'Update Status - ExpressTrack Device 2')

@section('content')
<!-- Header -->
<div class="text-center mb-8">
    <div class="inline-flex items-center justify-center w-16 h-16 gradient-bg rounded-2xl mb-4">
        <i class="fas fa-edit text-white text-2xl"></i>
    </div>
    <h1 class="text-4xl font-bold text-white mb-2">Update Status Pengiriman</h1>
    <p class="text-gray-400">{{ $shipment->tracking_number }}</p>
</div>

<!-- Current Status -->
<div class="max-w-2xl mx-auto mb-8">
    <div class="glass rounded-2xl p-6">
        <h2 class="text-xl font-bold text-white mb-4 text-center">Status Saat Ini</h2>
        <div class="flex justify-center">
            <span class="px-6 py-3 bg-{{ $shipment->status_color }}-500 bg-opacity-20 text-{{ $shipment->status_color }}-400 rounded-2xl text-lg font-semibold border border-{{ $shipment->status_color }}-500 border-opacity-30">
                {{ $shipment->status_icon }} {{ $shipment->status_label }}
            </span>
        </div>
        <div class="text-center mt-4 text-gray-400 text-sm">
            Terakhir diupdate: {{ $shipment->formatted_updated_at }}
        </div>
    </div>
</div>

<!-- Shipment Info -->
<div class="max-w-4xl mx-auto mb-8">
    <div class="glass rounded-2xl p-6">
        <h2 class="text-xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-info-circle text-blue-400 mr-3"></i>
            Informasi Pengiriman
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-400 text-sm mb-1">Pengirim</p>
                <p class="text-white font-medium">{{ $shipment->sender_name }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">Penerima</p>
                <p class="text-white font-medium">{{ $shipment->recipient_name }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">Berat</p>
                <p class="text-white font-medium">{{ $shipment->weight_kg }} kg</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm mb-1">Dibuat</p>
                <p class="text-white font-medium">{{ $shipment->formatted_created_at }}</p>
            </div>
        </div>
        @if($shipment->description)
        <div class="mt-4">
            <p class="text-gray-400 text-sm mb-1">Deskripsi</p>
            <p class="text-white">{{ $shipment->description }}</p>
        </div>
        @endif
    </div>
</div>

<!-- Status Update Form -->
<div class="max-w-2xl mx-auto">
    <div class="glass rounded-2xl p-8">
        <h2 class="text-2xl font-bold text-white mb-6 text-center">
            <i class="fas fa-sync-alt text-purple-400 mr-3"></i>
            Pilih Status Baru
        </h2>
        
        <form action="{{ route('shipments.update', $shipment) }}" method="POST" id="statusForm">
            @csrf
            @method('PUT')
            
            <div class="space-y-4 mb-8">
                @foreach(\App\Models\Shipment::getStatusOptions() as $value => $label)
                    @php
                        $colors = [
                            'pending' => 'yellow',
                            'in_transit' => 'blue', 
                            'delivered' => 'green'
                        ];
                        $icons = [
                            'pending' => '🕐',
                            'in_transit' => '🚚',
                            'delivered' => '✅'
                        ];
                        $color = $colors[$value];
                        $icon = $icons[$value];
                        $isSelected = $shipment->status == $value;
                    @endphp
                    
                    <label class="block cursor-pointer">
                        <input type="radio" name="status" value="{{ $value }}" 
                               class="sr-only peer" 
                               {{ $isSelected ? 'checked' : '' }}
                               {{ $isSelected ? 'disabled' : '' }}>
                        <div class="p-4 border-2 border-gray-600 rounded-xl transition-all duration-300 
                                    peer-checked:border-{{ $color }}-500 peer-checked:bg-{{ $color }}-500 peer-checked:bg-opacity-20
                                    {{ $isSelected ? 'opacity-50 cursor-not-allowed' : 'hover:border-gray-500' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="text-2xl">{{ $icon }}</span>
                                    <div>
                                        <p class="text-white font-semibold">{{ $label }}</p>
                                        <p class="text-gray-400 text-sm">
                                            @if($value == 'pending')
                                                Pengiriman sedang menunggu diproses
                                            @elseif($value == 'in_transit')
                                                Pengiriman sedang dalam perjalanan
                                            @else
                                                Pengiriman telah sampai di tujuan
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @if($isSelected)
                                    <span class="text-{{ $color }}-400 text-sm font-medium">Status Saat Ini</span>
                                @endif
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                <a href="{{ route('shipments.show', $shipment) }}" 
                   class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-xl transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" id="submitBtn"
                        class="px-8 py-3 gradient-bg text-white rounded-xl hover:opacity-90 transition-all duration-300 disabled:opacity-50">
                    <i class="fas fa-save mr-2"></i>
                    <span id="submitText">Update Status</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Device Info -->
<div class="max-w-2xl mx-auto mt-8">
    <div class="glass rounded-xl p-4">
        <div class="flex items-center justify-center space-x-2 text-sm text-gray-400">
            <i class="fas fa-database"></i>
            <span>Device 2 - PostgreSQL Primary</span>
            <span>•</span>
            <span>Status Update Only</span>
            <span>•</span>
            <span>Auto Sync to MySQL</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('statusForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    submitBtn.disabled = true;
    submitText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
});

// Check if any status is selected (other than current)
document.querySelectorAll('input[name="status"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const submitBtn = document.getElementById('submitBtn');
        const currentStatus = '{{ $shipment->status }}';
        
        if (this.value !== currentStatus) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50');
        }
    });
});

// Disable submit if current status is selected
document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.getElementById('submitBtn');
    const selectedRadio = document.querySelector('input[name="status"]:checked');
    const currentStatus = '{{ $shipment->status }}';
    
    if (selectedRadio && selectedRadio.value === currentStatus) {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50');
    }
});
</script>
@endpush
@endsection
