@extends('layouts.app')

@section('title', 'Tambah Pengiriman Baru')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Pengiriman Baru
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('shipments.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3">
                        <!-- Informasi Pengiriman -->
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Informasi Pengiriman
                            </h5>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="id" class="form-label">ID Pengiriman <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('id') is-invalid @enderror" 
                                   id="id" name="id" value="{{ old('id', $nextId) }}" 
                                   placeholder="1" required>
                            @error('id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">ID unik untuk pengiriman ini</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="tracking_number" class="form-label">Nomor Resi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tracking_number') is-invalid @enderror" 
                                   id="tracking_number" name="tracking_number" value="{{ old('tracking_number') }}" 
                                   placeholder="EXP12345678" required>
                            @error('tracking_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="weight" class="form-label">Berat (kg) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('weight') is-invalid @enderror" 
                                   id="weight" name="weight" value="{{ old('weight') }}" 
                                   step="0.01" min="0.01" required>
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                @foreach(\App\Models\Shipment::getStatusOptions() as $value => $label)
                                    <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label for="description" class="form-label">Deskripsi Barang</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Deskripsi barang yang dikirim">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Informasi Pengirim -->
                        <div class="col-12 mt-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user me-2"></i>
                                Informasi Pengirim
                            </h5>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="sender_name" class="form-label">Nama Pengirim <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sender_name') is-invalid @enderror" 
                                   id="sender_name" name="sender_name" value="{{ old('sender_name') }}" required>
                            @error('sender_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="sender_phone" class="form-label">Telepon Pengirim <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sender_phone') is-invalid @enderror" 
                                   id="sender_phone" name="sender_phone" value="{{ old('sender_phone') }}" required>
                            @error('sender_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label for="sender_address" class="form-label">Alamat Pengirim <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('sender_address') is-invalid @enderror" 
                                      id="sender_address" name="sender_address" rows="2" required>{{ old('sender_address') }}</textarea>
                            @error('sender_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Informasi Penerima -->
                        <div class="col-12 mt-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user-check me-2"></i>
                                Informasi Penerima
                            </h5>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="recipient_name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" 
                                   id="recipient_name" name="recipient_name" value="{{ old('recipient_name') }}" required>
                            @error('recipient_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="recipient_phone" class="form-label">Telepon Penerima <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('recipient_phone') is-invalid @enderror" 
                                   id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone') }}" required>
                            @error('recipient_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12">
                            <label for="recipient_address" class="form-label">Alamat Penerima <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('recipient_address') is-invalid @enderror" 
                                      id="recipient_address" name="recipient_address" rows="2" required>{{ old('recipient_address') }}</textarea>
                            @error('recipient_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('shipments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i>
                            Simpan Pengiriman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-generate tracking number based on ID
document.getElementById('id').addEventListener('input', function() {
    const id = this.value;
    if (id) {
        const trackingNumber = 'EXP' + String(id).padStart(8, '0');
        document.getElementById('tracking_number').value = trackingNumber;
    }
});

// Trigger on page load if ID already has value
document.addEventListener('DOMContentLoaded', function() {
    const idField = document.getElementById('id');
    if (idField.value) {
        idField.dispatchEvent(new Event('input'));
    }
});
</script>
@endpush
@endsection
