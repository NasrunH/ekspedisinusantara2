<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

        $shipments = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('shipments.index', compact('shipments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get next available ID
        $nextId = $this->getNextId();
        return view('shipments.create', compact('nextId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|unique:shipments',
            'tracking_number' => 'required|string|max:255|unique:shipments',
            'sender_name' => 'required|string|max:255',
            'sender_address' => 'required|string',
            'sender_phone' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'recipient_address' => 'required|string',
            'recipient_phone' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_transit,delivered',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Konversi weight dari kg ke gram
            $data = $request->all();
            $data['weight'] = $request->weight * 1000; // Convert kg to grams
            
            // Set tanggal dengan format yang benar
            $data['created_at'] = now()->format('Y-m-d');
            $data['updated_at'] = now()->format('Y-m-d');

            $shipment = Shipment::create($data);

            DB::commit();

            return redirect()->route('shipments.index')
                ->with('success', 'Pengiriman berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Gagal menambahkan pengiriman: ' . $e->getMessage())
                ->withInput();
        }
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
     */
    public function edit(Shipment $shipment)
    {
        return view('shipments.edit', compact('shipment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shipment $shipment)
    {
        $validator = Validator::make($request->all(), [
            'tracking_number' => 'required|string|max:255|unique:shipments,tracking_number,' . $shipment->id,
            'sender_name' => 'required|string|max:255',
            'sender_address' => 'required|string',
            'sender_phone' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'recipient_address' => 'required|string',
            'recipient_phone' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_transit,delivered',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Konversi weight dari kg ke gram
            $data = $request->all();
            $data['weight'] = $request->weight * 1000; // Convert kg to grams
            $data['updated_at'] = now()->format('Y-m-d');

            $shipment->update($data);

            DB::commit();

            return redirect()->route('shipments.show', $shipment)
                ->with('success', 'Pengiriman berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui pengiriman: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment)
    {
        try {
            DB::beginTransaction();

            $shipment->delete();

            DB::commit();

            return redirect()->route('shipments.index')
                ->with('success', 'Pengiriman berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus pengiriman: ' . $e->getMessage());
        }
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
                'sender_name' => $shipment->sender_name,
                'recipient_name' => $shipment->recipient_name,
                'weight' => number_format($shipment->weight / 1000, 2),
                'created_at' => $shipment->created_at,
                'updated_at' => $shipment->updated_at,
            ]
        ]);
    }

    /**
     * Sync data between databases
     */
    public function syncDatabases()
    {
        try {
            $deviceName = config('app.device_name', 'device_1');
            
            if ($deviceName === 'device_1') {
                // Sync MySQL to PostgreSQL
                $mysqlShipments = DB::connection('mysql')->table('shipments')->get();
                
                foreach ($mysqlShipments as $shipment) {
                    DB::connection('pgsql')->table('shipments')->updateOrInsert(
                        ['id' => $shipment->id],
                        (array) $shipment
                    );
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Sinkronisasi MySQL ke PostgreSQL berhasil',
                    'synced_records' => $mysqlShipments->count()
                ]);
            } else {
                // Sync PostgreSQL to MySQL
                $postgresShipments = DB::connection('pgsql')->table('shipments')->get();
                
                foreach ($postgresShipments as $shipment) {
                    DB::connection('mysql_secondary')->table('shipments')->updateOrInsert(
                        ['id' => $shipment->id],
                        (array) $shipment
                    );
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Sinkronisasi PostgreSQL ke MySQL berhasil',
                    'synced_records' => $postgresShipments->count()
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkronisasi database: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get next available ID
     */
    private function getNextId()
    {
        $lastShipment = Shipment::orderBy('id', 'desc')->first();
        return $lastShipment ? $lastShipment->id + 1 : 1;
    }
}
