<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Terima data dari device lain
     */
    public function receiveData(Request $request)
    {
        try {
            $data = $request->validate([
                'action' => 'required|in:create,update,delete',
                'shipment' => 'required|array',
                'source_device' => 'required|string'
            ]);

            Log::info('Webhook received', $data);

            switch ($data['action']) {
                case 'create':
                case 'update':
                    Shipment::updateOrCreate(
                        ['id' => $data['shipment']['id']],
                        $data['shipment']
                    );
                    break;
                
                case 'delete':
                    Shipment::where('id', $data['shipment']['id'])->delete();
                    break;
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Kirim data ke device lain
     */
    public function sendToRemoteDevice($action, $shipment, $remoteUrl)
    {
        try {
            $response = Http::timeout(10)->post($remoteUrl . '/webhook/receive', [
                'action' => $action,
                'shipment' => $shipment->toArray(),
                'source_device' => config('app.device_name', 'device_1')
            ]);

            if ($response->successful()) {
                Log::info("Data berhasil dikirim ke {$remoteUrl}");
                return true;
            } else {
                Log::error("Gagal mengirim data ke {$remoteUrl}: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Error mengirim data ke {$remoteUrl}: " . $e->getMessage());
            return false;
        }
    }
}