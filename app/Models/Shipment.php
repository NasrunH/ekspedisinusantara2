<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'weight' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
     * Replikasi data ke database lain
     */
    public function replicateToSecondaryDatabase()
    {
        try {
            // Replikasi ke PostgreSQL
            DB::connection('pgsql')->table('shipments')->updateOrInsert(
                ['id' => $this->id],
                $this->toArray()
            );
            
            \Log::info("Data shipment ID {$this->id} berhasil direplikasi ke PostgreSQL");
        } catch (\Exception $e) {
            \Log::error("Gagal mereplikasi data shipment ID {$this->id} ke PostgreSQL: " . $e->getMessage());
        }
    }

    /**
     * Event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Replikasi setelah create
        static::created(function ($shipment) {
            $shipment->replicateToSecondaryDatabase();
        });

        // Replikasi setelah update
        static::updated(function ($shipment) {
            $shipment->replicateToSecondaryDatabase();
        });

        // Replikasi setelah delete
        static::deleted(function ($shipment) {
            try {
                DB::connection('pgsql')->table('shipments')->where('id', $shipment->id)->delete();
                \Log::info("Data shipment ID {$shipment->id} berhasil dihapus dari PostgreSQL");
            } catch (\Exception $e) {
                \Log::error("Gagal menghapus data shipment ID {$shipment->id} dari PostgreSQL: " . $e->getMessage());
            }
        });
    }
}
