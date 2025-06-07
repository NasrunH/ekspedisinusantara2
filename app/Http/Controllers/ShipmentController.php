<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Shipment::query();

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Pencarian
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $shipments = $query->orderBy('created_at', 'desc')->paginate(12);

        // Get statistics from PostgreSQL
        $stats = [
            'total' => Shipment::count(),
            'pending' => Shipment::where('status', 'pending')->count(),
            'in_transit' => Shipment::where('status', 'in_transit')->count(),
            'delivered' => Shipment::where('status', 'delivered')->count(),
        ];

        return view('shipments.index', compact('shipments', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     * DISABLED for Device 2 - Only status updates allowed
     */
    public function create()
    {
        return redirect()->route('shipments.index')
            ->with('error', 'Device 2 hanya dapat mengupdate status pengiriman. Untuk membuat pengiriman baru, gunakan Device 1.');
    }

    /**
     * Store a newly created resource in storage.
     * DISABLED for Device 2 - Only status updates allowed
     */
    public function store(Request $request)
    {
        return redirect()->route('shipments.index')
            ->with('error', 'Device 2 hanya dapat mengupdate status pengiriman. Untuk membuat pengiriman baru, gunakan Device 1.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment)
    {
        return view('shipments.show', compact('shipment'));
    }

    /**
     * Show the form for editing the specified resource.
     * MODIFIED for Device 2 - Only status editing allowed
     */
    public function edit(Shipment $shipment)
    {
        return view('shipments.edit-status', compact('shipment'));
    }

    /**
     * Update the specified resource in storage.
     * MODIFIED for Device 2 - Only status updates allowed
     */
    public function update(Request $request, Shipment $shipment)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_transit,delivered',
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Status yang dipilih tidak valid.');
        }

        try {
            DB::beginTransaction();

            $oldStatus = $shipment->status;
            $newStatus = $request->status;

            // Only update status
            $shipment->update([
                'status' => $newStatus
            ]);

            DB::commit();

            Log::info("[Device 2] Status updated successfully", [
                'shipment_id' => $shipment->id,
                'tracking_number' => $shipment->tracking_number,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'updated_by' => 'Device 2'
            ]);

            return redirect()->route('shipments.show', $shipment)
                ->with('success', "Status pengiriman {$shipment->tracking_number} berhasil diubah dari {$oldStatus} ke {$newStatus}! 🔄");
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('[Device 2] Error updating status: ' . $e->getMessage(), [
                'shipment_id' => $shipment->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * DISABLED for Device 2 - Only status updates allowed
     */
    public function destroy(Shipment $shipment)
    {
        return redirect()->route('shipments.index')
            ->with('error', 'Device 2 hanya dapat mengupdate status pengiriman. Untuk menghapus pengiriman, gunakan Device 1.');
    }

    /**
     * API endpoint untuk tracking
     */
    public function track(Request $request)
    {
        $trackingNumber = $request->input('tracking_number');
        
        if (!$trackingNumber) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor resi diperlukan'
            ], 400);
        }

        $shipment = Shipment::where('tracking_number', $trackingNumber)->first();

        if (!$shipment) {
            return response()->json([
                'success' => false,
                'message' => 'Pengiriman tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'tracking_number' => $shipment->tracking_number,
                'status' => $shipment->status,
                'status_label' => $shipment->status_label,
                'status_icon' => $shipment->status_icon,
                'sender_name' => $shipment->sender_name,
                'recipient_name' => $shipment->recipient_name,
                'weight' => $shipment->weight_kg,
                'description' => $shipment->description,
                'created_at' => $shipment->formatted_created_at,
                'updated_at' => $shipment->formatted_updated_at,
            ]
        ]);
    }

    /**
     * Sync databases - Pull data from MySQL to PostgreSQL
     */
    public function syncDatabases(Request $request)
    {
        try {
            Log::info('[Device 2] Sync request received - Pulling from MySQL to PostgreSQL');

            // Test connections
            try {
                DB::connection('mysql')->select('SELECT 1 as test');
                Log::info('[Device 2] MySQL connection OK');
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Koneksi MySQL gagal: ' . $e->getMessage()
                ], 500);
            }

            try {
                DB::connection('pgsql')->select('SELECT 1 as test');
                Log::info('[Device 2] PostgreSQL connection OK');
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Koneksi PostgreSQL gagal: ' . $e->getMessage()
                ], 500);
            }

            // Pull data from MySQL (Device 1) to PostgreSQL (Device 2)
            $mysqlShipments = DB::connection('mysql')->table('shipments')->get();
            $syncCount = 0;
            $errors = [];

            foreach ($mysqlShipments as $shipment) {
                try {
                    $shipmentArray = (array) $shipment;
                    
                    $exists = DB::connection('pgsql')
                        ->table('shipments')
                        ->where('id', $shipment->id)
                        ->exists();
                    
                    if ($exists) {
                        // Update existing record
                        DB::connection('pgsql')
                            ->table('shipments')
                            ->where('id', $shipment->id)
                            ->update($shipmentArray);
                    } else {
                        // Insert new record
                        DB::connection('pgsql')
                            ->table('shipments')
                            ->insert($shipmentArray);
                    }
                    
                    $syncCount++;
                } catch (\Exception $e) {
                    $errors[] = "Error sync ID {$shipment->id}: " . $e->getMessage();
                }
            }

            $response = [
                'success' => true,
                'message' => 'Sinkronisasi berhasil! Data ditarik dari MySQL ke PostgreSQL 🔄',
                'synced_records' => $syncCount,
                'total_records' => $mysqlShipments->count(),
                'device' => 'Device 2 - Status Update Interface',
                'sync_direction' => 'MySQL → PostgreSQL',
                'timestamp' => now()->format('d/m/Y H:i:s')
            ];

            if (!empty($errors)) {
                $response['warnings'] = $errors;
            }

            Log::info('[Device 2] Sync completed', $response);
            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('[Device 2] Sync failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick status update API
     */
    public function updateStatus(Request $request, Shipment $shipment)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_transit,delivered',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid'
            ], 400);
        }

        try {
            $oldStatus = $shipment->status;
            $newStatus = $request->status;

            $shipment->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => "Status berhasil diubah dari {$oldStatus} ke {$newStatus}",
                'data' => [
                    'id' => $shipment->id,
                    'tracking_number' => $shipment->tracking_number,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'status_label' => $shipment->status_label,
                    'status_icon' => $shipment->status_icon,
                    'updated_at' => $shipment->formatted_updated_at
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get next available ID
     */
    private function getNextId()
    {
        try {
            $lastShipment = Shipment::orderBy('id', 'desc')->first();
            return $lastShipment ? $lastShipment->id + 1 : 1;
        } catch (\Exception $e) {
            return 1;
        }
    }
}
