<?php

namespace Database\Seeders;

use App\Models\Shipment;
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shipments = [
            [
                'tracking_number' => 'EXP12345678',
                'sender_name' => 'PT. Maju Jaya',
                'sender_address' => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta',
                'sender_phone' => '08123456789',
                'recipient_name' => 'Budi Santoso',
                'recipient_address' => 'Jl. Gatot Subroto No. 45, Bandung, Jawa Barat',
                'recipient_phone' => '08987654321',
                'weight' => 2.5,
                'description' => 'Paket dokumen penting dan kontrak bisnis',
                'status' => 'in_transit',
            ],
            [
                'tracking_number' => 'EXP87654321',
                'sender_name' => 'CV. Berkah Abadi',
                'sender_address' => 'Jl. Thamrin No. 45, Jakarta Selatan, DKI Jakarta',
                'sender_phone' => '08111222333',
                'recipient_name' => 'Siti Aminah',
                'recipient_address' => 'Jl. Ahmad Yani No. 78, Surabaya, Jawa Timur',
                'recipient_phone' => '08444555666',
                'weight' => 5.0,
                'description' => 'Paket elektronik - Laptop dan aksesoris',
                'status' => 'delivered',
            ],
            [
                'tracking_number' => 'EXP55667788',
                'sender_name' => 'Toko Makmur',
                'sender_address' => 'Jl. Diponegoro No. 67, Semarang, Jawa Tengah',
                'sender_phone' => '08777888999',
                'recipient_name' => 'Ahmad Rizki',
                'recipient_address' => 'Jl. Pahlawan No. 12, Yogyakarta, DI Yogyakarta',
                'recipient_phone' => '08222333444',
                'weight' => 1.2,
                'description' => 'Paket buku dan alat tulis',
                'status' => 'pending',
            ],
            [
                'tracking_number' => 'EXP99887766',
                'sender_name' => 'UD. Sejahtera',
                'sender_address' => 'Jl. Veteran No. 89, Malang, Jawa Timur',
                'sender_phone' => '08333444555',
                'recipient_name' => 'Dewi Lestari',
                'recipient_address' => 'Jl. Merdeka No. 34, Denpasar, Bali',
                'recipient_phone' => '08666777888',
                'weight' => 3.8,
                'description' => 'Paket makanan khas daerah',
                'status' => 'in_transit',
            ],
            [
                'tracking_number' => 'EXP11223344',
                'sender_name' => 'PT. Teknologi Maju',
                'sender_address' => 'Jl. Kuningan No. 56, Jakarta Selatan, DKI Jakarta',
                'sender_phone' => '08999000111',
                'recipient_name' => 'Eko Prasetyo',
                'recipient_address' => 'Jl. Sudirman No. 123, Medan, Sumatera Utara',
                'recipient_phone' => '08111000999',
                'weight' => 0.8,
                'description' => 'Paket spare part komputer',
                'status' => 'delivered',
            ],
        ];

        foreach ($shipments as $shipment) {
            Shipment::create($shipment);
        }
    }
}
