@extends('layouts.app')

@section('title', 'Edit Pengiriman - Ekspedisi Nusantara')

@section('content')
<!-- Header -->
<div class="flex items-center space-x-4 mb-8">
    <a href="{{ route('shipments.show', $shipment) }}" 
       class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Edit Pengiriman</h1>
        <p class="text-gray-600">{{ $shipment->tracking_number }} - Perbarui informasi pengiriman</p>
    </div>
</div>

<!-- Current Status Info -->
<div class="bg-gradient-to-r from-primary-600 to-secondary-600 rounded-xl p-6 text-white mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                <i class="fas fa-info-circle text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold">Status Saat Ini</h3>
                <p class="opacity-90">{{ $shipment->status_icon }} {{ $shipment->status_label }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-sm opacity-75">Terakhir diupdate</p>
            <p class="font-semibold">{{ $shipment->formatted_updated_at }}</p>
        </div>
    </div>
</div>

<!-- Form -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <form action="{{ route('shipments.update', $shipment) }}" method="POST" class="p-8 space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Informasi Paket -->
        <div class="space-y-6">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-box text-primary-600 text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Informasi Paket</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ID Pengiriman
                    </label>
                    <input type="text" value="{{ $shipment->id }}" disabled
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                    <p class="text-gray-500 text-sm mt-1">ID tidak dapat diubah</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Resi
                    </label>
                    <input type="text" value="{{ $shipment->tracking_number }}" disabled
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                    <p class="text-gray-500 text-sm mt-1">Nomor resi tidak dapat diubah</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Berat (kg)
                    </label>
                    <input type="text" value="{{ number_format($shipment->weight / 1000, 2) }}" disabled
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                    <p class="text-gray-500 text-sm mt-1">Berat tidak dapat diubah</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('status') border-red-500 @enderror">
                        @foreach(\App\Models\Shipment::getStatusOptions() as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $shipment->status) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Barang</label>
                <textarea name="description" rows="4" disabled
                          class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">{{ old('description', $shipment->description) }}</textarea>
                <p class="text-gray-500 text-sm mt-1">Deskripsi tidak dapat diubah</p>
            </div>
        </div>

        <!-- Data Pengirim -->
        <div class="space-y-6 pt-8 border-t border-gray-200">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-user text-blue-600 text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Data Pengirim</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Pengirim
                    </label>
                    <input type="text" value="{{ $shipment->sender_name }}" disabled
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Telepon Pengirim
                    </label>
                    <input type="text" value="{{ $shipment->sender_phone }}" disabled
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Alamat Pengirim
                </label>
                <textarea rows="3" disabled
                          class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">{{ old('sender_address', $shipment->sender_address) }}</textarea>
            </div>
        </div>

        <!-- Data Penerima -->
        <div class="space-y-6 pt-8 border-t border-gray-200">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Data Penerima</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Penerima
                    </label>
                    <input type="text" value="{{ $shipment->recipient_name }}" disabled
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Telepon Penerima
                    </label>
                    <input type="text" value="{{ $shipment->recipient_phone }}" disabled
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Alamat Penerima
                </label>
                <textarea rows="3" disabled
                          class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed">{{ old('recipient_address', $shipment->recipient_address) }}</textarea>
            </div>
        </div>

        <!-- Change Log -->
        <div class="pt-8 border-t border-gray-200">
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-amber-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="text-amber-800 font-medium">Perhatian</h4>
                        <p class="text-amber-700 text-sm mt-1">
                            Perubahan data akan otomatis tersinkronisasi ke semua sistem database. 
                            Pastikan data yang dimasukkan sudah benar sebelum menyimpan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center pt-8 border-t border-gray-200">
            <div class="flex items-center space-x-4">
                <a href="{{ route('shipments.show', $shipment) }}" 
                   class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="button" onclick="resetForm()" 
                        class="px-6 py-3 bg-amber-100 hover:bg-amber-200 text-amber-700 font-medium rounded-lg transition-colors">
                    <i class="fas fa-undo mr-2"></i>
                    Reset
                </button>
            </div>
            
            <div class="flex items-center space-x-4">
                <button type="button" onclick="previewChanges()" 
                        class="px-6 py-3 bg-secondary-600 hover:bg-secondary-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-eye mr-2"></i>
                    Preview
                </button>
                <button type="submit" id="submitBtn"
                        class="px-8 py-3 gradient-bg text-white font-semibold rounded-lg hover:opacity-90 transition-all duration-300 shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    <span id="submitText">Simpan Perubahan</span>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Preview Perubahan</h3>
            <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="previewContent" class="space-y-4">
            <!-- Preview content will be inserted here -->
        </div>
        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
            <button onclick="closePreview()" 
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                Tutup
            </button>
            <button onclick="submitForm()" 
                    class="px-6 py-2 gradient-bg text-white rounded-lg hover:opacity-90 transition-all">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Form submission handling
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    submitBtn.disabled = true;
    submitText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
});

// Reset form to original values
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset form ke nilai awal?')) {
        location.reload();
    }
}

// Preview changes
function previewChanges() {
    const form = document.querySelector('form');
    const formData = new FormData(form);
    
    const originalData = {
        status: '{{ $shipment->status }}',
    };
    
    const newData = {
        status: formData.get('status'),
    };
    
    const statusLabels = {
        'pending': 'Menunggu',
        'in_transit': 'Dalam Pengiriman',
        'delivered': 'Terkirim'
    };
    
    let changes = [];
    
    // Check for changes
    Object.keys(originalData).forEach(key => {
        if (originalData[key] !== newData[key]) {
            let oldValue = originalData[key];
            let newValue = newData[key];
            
            if (key === 'status') {
                oldValue = statusLabels[oldValue] || oldValue;
                newValue = statusLabels[newValue] || newValue;
            }
            
            changes.push({
                field: key,
                label: getFieldLabel(key),
                old: oldValue,
                new: newValue
            });
        }
    });
    
    if (changes.length === 0) {
        alert('Tidak ada perubahan yang terdeteksi.');
        return;
    }
    
    // Generate preview content
    let previewHTML = '<div class="space-y-4">';
    
    changes.forEach(change => {
        previewHTML += `
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-2">${change.label}</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Sebelum:</p>
                        <p class="text-red-600 font-medium">${change.old || '-'}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Sesudah:</p>
                        <p class="text-green-600 font-medium">${change.new || '-'}</p>
                    </div>
                </div>
            </div>
        `;
    });
    
    previewHTML += '</div>';
    
    document.getElementById('previewContent').innerHTML = previewHTML;
    document.getElementById('previewModal').classList.remove('hidden');
    document.getElementById('previewModal').classList.add('flex');
}

function getFieldLabel(field) {
    const labels = {
        'status': 'Status',
    };
    return labels[field] || field;
}

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
    document.getElementById('previewModal').classList.remove('flex');
}

function submitForm() {
    closePreview();
    document.querySelector('form').submit();
}

// Close modal when clicking outside
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreview();
    }
});
</script>
@endpush
@endsection