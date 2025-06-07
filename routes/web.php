<?php

use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Shipment routes - Modified for Device 2 (Status Update Only)
Route::resource('shipments', ShipmentController::class)->except(['create', 'store', 'destroy']);

// API routes
Route::get('/api/track', [ShipmentController::class, 'track'])->name('api.track');
Route::post('/api/sync-databases', [ShipmentController::class, 'syncDatabases'])->name('api.sync');
Route::put('/api/shipments/{shipment}/status', [ShipmentController::class, 'updateStatus'])->name('api.update-status');
