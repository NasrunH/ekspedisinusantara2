<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    // Disable Laravel's automatic timestamps
    public $timestamps = false;
    
    // Allow mass assignment for id
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
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'date',
        'updated_at' => 'date',
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
     * Get weight in kg
     */
    public function getWeightKgAttribute()
    {
        return number_format($this->weight / 1000, 2);
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
}
