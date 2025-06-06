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

// Shipment routes
Route::resource('shipments', ShipmentController::class);

// API routes untuk tracking
Route::get('/api/track', [ShipmentController::class, 'track'])->name('api.track');
Route::post('/api/sync-databases', [ShipmentController::class, 'syncDatabases'])->name('api.sync');
