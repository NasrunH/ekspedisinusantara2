@extends('layouts.app')

@section('title', 'Daftar Pengiriman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-list me-2"></i>
        Daftar Pengiriman
    </h2>
    <a href="{{ route('shipments.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-1"></i>
        Tambah Pengiriman
    </a>
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('shipments.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Pencarian</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari berdasarkan resi, pengirim, atau penerima">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        @foreach(\App\Models\Shipment::getStatusOptions() as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>
                        Filter
                    </button>
                    <a href="{{ route('shipments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>
                        Reset
                    </a>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-info" onclick="syncDatabases()">
                        <i class="fas fa-sync-alt me-1"></i>
                        Sync DB
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Pengiriman -->
<div class="card">
    <div class="card-body">
        @if($shipments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No. Resi</th>
                            <th>Pengirim</th>
                            <th>Penerima</th>
                            <th>Berat</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipments as $shipment)
                            <tr>
                                <td>
                                    <strong>{{ $shipment->tracking_number }}</strong>
                                </td>
                                <td>{{ $shipment->sender_name }}</td>
                                <td>{{ $shipment->recipient_name }}</td>
                                <td>{{ number_format($shipment->weight / 1000, 2) }} kg</td>
                                <td>
                                    @php
                                        $badgeClass = 'warning';
                                        if($shipment->status == 'in_transit') $badgeClass = 'info';
                                        if($shipment->status == 'delivered') $badgeClass = 'success';
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }} status-badge">
                                        {{ $shipment->status_label }}
                                    </span>
                                </td>
                                <td>{{ $shipment->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('shipments.show', $shipment) }}" 
                                           class="btn btn-outline-primary" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('shipments.edit', $shipment) }}" 
                                           class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="deleteShipment({{ $shipment->id }})" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <small class="text-muted">
                        Menampilkan {{ $shipments->firstItem() }} - {{ $shipments->lastItem() }} 
                        dari {{ $shipments->total() }} pengiriman
                    </small>
                </div>
                <div>
                    {{ $shipments->appends(request()->query())->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">Belum ada data pengiriman</h4>
                <p class="text-muted">Silakan tambahkan pengiriman baru untuk memulai.</p>
                <a href="{{ route('shipments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Tambah Pengiriman Pertama
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pengiriman ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteShipment(id) {
    const form = document.getElementById('deleteForm');
    form.action = `/shipments/${id}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function syncDatabases() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Syncing...';
    button.disabled = true;
    
    fetch('/api/sync-databases', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Sinkronisasi berhasil! ${data.synced_records} record telah disinkronkan.`);
        } else {
            alert('Gagal melakukan sinkronisasi: ' + data.message);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan saat sinkronisasi database.');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}
</script>
@endpush
@endsection
