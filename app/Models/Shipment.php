<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Shipment extends Model
{
    // Use PostgreSQL as default connection for Device 2
    protected $connection = 'pgsql';
    
    protected $fillable = [
        'id',
        'tracking_number',
        'sender_name',
        'sender_address',
        'sender_phone',
        'recipient_name',
        'recipient_address',
        'recipient_phone',
        'weight',
        'description',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'weight' => 'integer',
    ];

    /**
     * Status options
     */
    public static function getStatusOptions()
    {
        return [
            'pending' => 'Menunggu',
            'in_transit' => 'Dalam Pengiriman',
            'delivered' => 'Terkirim',
        ];
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $statuses = self::getStatusOptions();
        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get status icon
     */
    public function getStatusIconAttribute()
    {
        $icons = [
            'pending' => '🕐',
            'in_transit' => '🚚',
            'delivered' => '✅',
        ];
        return $icons[$this->status] ?? '📦';
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'in_transit' => 'blue',
            'delivered' => 'green',
        ];
        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Get weight in kg
     */
    public function getWeightKgAttribute()
    {
        return number_format($this->weight / 1000, 2);
    }

    /**
     * Get formatted created at
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }

    /**
     * Get formatted updated at
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at->format('d/m/Y H:i:s');
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('tracking_number', 'like', "%{$search}%")
              ->orWhere('sender_name', 'like', "%{$search}%")
              ->orWhere('recipient_name', 'like', "%{$search}%");
        });
    }

    /**
     * Boot the model - Auto sync to MySQL (Device 1)
     */
    protected static function boot()
    {
        parent::boot();
        
        // Only sync status updates to MySQL
        static::updated(function ($shipment) {
            try {
                // Only sync if status was changed
                if ($shipment->isDirty('status')) {
                    $attributes = [
                        'status' => $shipment->status,
                        'updated_at' => $shipment->updated_at->format('Y-m-d H:i:s'),
                    ];
                    
                    // Update status in MySQL (Device 1)
                    $updated = DB::connection('mysql')
                        ->table('shipments')
                        ->where('id', $shipment->id)
                        ->update($attributes);
                    
                    if ($updated) {
                        Log::info("[Device 2] Status updated and synced to MySQL", [
                            'shipment_id' => $shipment->id,
                            'new_status' => $shipment->status,
                            'tracking_number' => $shipment->tracking_number
                        ]);
                    } else {
                        Log::warning("[Device 2] Failed to sync status to MySQL - record not found", [
                            'shipment_id' => $shipment->id
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error("[Device 2] Failed to sync status to MySQL: " . $e->getMessage(), [
                    'shipment_id' => $shipment->id,
                    'error' => $e->getMessage()
                ]);
            }
        });
    }
}
