@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Shipment</h1>

    <form action="{{ route('shipments.update', $shipment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <label for="tracking_number" class="form-label">Tracking Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('tracking_number') is-invalid @enderror" id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $shipment->tracking_number) }}" required>
                @error('tracking_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="shipping_date" class="form-label">Shipping Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('shipping_date') is-invalid @enderror" id="shipping_date" name="shipping_date" value="{{ old('shipping_date', $shipment->shipping_date) }}" required>
                @error('shipping_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="delivery_date" class="form-label">Delivery Date</label>
                <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" id="delivery_date" name="delivery_date" value="{{ old('delivery_date', $shipment->delivery_date) }}">
                @error('delivery_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="pending" {{ old('status', $shipment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_transit" {{ old('status', $shipment->status) == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="delivered" {{ old('status', $shipment->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="delayed" {{ old('status', $shipment->status) == 'delayed' ? 'selected' : '' }}>Delayed</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="recipient_name" class="form-label">Recipient Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" id="recipient_name" name="recipient_name" value="{{ old('recipient_name', $shipment->recipient_name) }}" required>
                @error('recipient_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="recipient_address" class="form-label">Recipient Address <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('recipient_address') is-invalid @enderror" id="recipient_address" name="recipient_address" value="{{ old('recipient_address', $shipment->recipient_address) }}" required>
                @error('recipient_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="weight" class="form-label">Berat (kg) <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('weight') is-invalid @enderror" 
                       id="weight" name="weight" value="{{ old('weight', number_format($shipment->weight / 1000, 2)) }}" 
                       step="0.01" min="0.01" required>
                @error('weight')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Masukkan berat dalam kilogram (kg)</small>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Shipment</button>
        <a href="{{ route('shipments.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
