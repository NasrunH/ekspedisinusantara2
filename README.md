# Aplikasi Ekspedisi Pengiriman Laravel

Aplikasi simulasi ekspedisi pengiriman barang dengan replikasi database antara MySQL dan PostgreSQL.

## Fitur Utama

- ✅ Manajemen pengiriman (CRUD)
- ✅ Update status pengiriman
- ✅ Pelacakan pengiriman
- ✅ Replikasi otomatis antara MySQL dan PostgreSQL
- ✅ Sinkronisasi manual database
- ✅ Responsive design
- ✅ API untuk tracking

## Persyaratan Sistem

- PHP 8.1 atau lebih baru
- Composer
- MySQL 5.7 atau lebih baru
- PostgreSQL 12 atau lebih baru
- Extension PHP: PDO, pdo_mysql, pdo_pgsql

## Instalasi

### 1. Clone atau Download Project

\`\`\`bash
# Jika menggunakan Git
git clone <repository-url>
cd laravel-expedition

# Atau extract file ZIP ke folder project
\`\`\`

### 2. Install Dependencies

\`\`\`bash
composer install
\`\`\`

### 3. Konfigurasi Environment

\`\`\`bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
\`\`\`

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

\`\`\`env
# Database MySQL (Primary)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expedition_mysql
DB_USERNAME=root
DB_PASSWORD=your_password

# Database PostgreSQL (Secondary)
DB_POSTGRES_CONNECTION=pgsql
DB_POSTGRES_HOST=127.0.0.1
DB_POSTGRES_PORT=5432
DB_POSTGRES_DATABASE=expedition_postgres
DB_POSTGRES_USERNAME=postgres
DB_POSTGRES_PASSWORD=your_password
\`\`\`

### 5. Buat Database

Buat database di MySQL dan PostgreSQL:

\`\`\`sql
-- MySQL
CREATE DATABASE expedition_mysql;

-- PostgreSQL
CREATE DATABASE expedition_postgres;
\`\`\`

### 6. Jalankan Migrasi

\`\`\`bash
# Migrasi ke MySQL
php artisan migrate --database=mysql

# Migrasi ke PostgreSQL
php artisan migrate --database=pgsql
\`\`\`

### 7. Isi Data Awal (Opsional)

\`\`\`bash
# Seed data ke MySQL
php artisan db:seed --database=mysql

# Seed data ke PostgreSQL
php artisan db:seed --database=pgsql
\`\`\`

### 8. Jalankan Aplikasi

\`\`\`bash
# Jalankan server lokal
php artisan serve

# Untuk akses dari device lain
php artisan serve --host=0.0.0.0 --port=8000
\`\`\`

Aplikasi akan berjalan di `http://localhost:8000`

## Penggunaan

### 1. Menambah Pengiriman Baru

1. Klik "Tambah Pengiriman" di halaman utama
2. Isi form dengan data pengiriman
3. Klik "Simpan Pengiriman"
4. Data akan otomatis tersimpan di kedua database

### 2. Melihat Daftar Pengiriman

1. Klik "Daftar Pengiriman" di menu
2. Gunakan filter untuk mencari pengiriman tertentu
3. Klik "Detail" untuk melihat informasi lengkap

### 3. Update Status Pengiriman

1. Buka detail pengiriman
2. Klik "Edit Pengiriman"
3. Ubah status pengiriman
4. Simpan perubahan

### 4. Pelacakan Pengiriman

1. Masukkan nomor resi di form tracking di halaman utama
2. Klik "Lacak"
3. Informasi pengiriman akan ditampilkan

### 5. Sinkronisasi Database Manual

\`\`\`bash
# Sinkronisasi kedua arah
php artisan db:sync

# Sinkronisasi MySQL ke PostgreSQL
php artisan db:sync --direction=mysql-to-postgres

# Sinkronisasi PostgreSQL ke MySQL
php artisan db:sync --direction=postgres-to-mysql
\`\`\`

## API Endpoints

### Tracking Pengiriman

\`\`\`
GET /api/track?tracking_number=EXP12345678
\`\`\`

Response:
\`\`\`json
{
    "success": true,
    "data": {
        "tracking_number": "EXP12345678",
        "status": "in_transit",
        "status_label": "Dalam Pengiriman",
        "sender_name": "PT. Maju Jaya",
        "recipient_name": "Budi Santoso",
        "weight": "2.50",
        "created_at": "01/06/2024 10:30",
        "updated_at": "02/06/2024 14:15"
    }
}
\`\`\`

### Sinkronisasi Database

\`\`\`
POST /api/sync-databases
\`\`\`

## Struktur Database

### Tabel: shipments

| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| tracking_number | varchar(20) | Nomor resi (unique) |
| sender_name | varchar(100) | Nama pengirim |
| sender_address | text | Alamat pengirim |
| sender_phone | varchar(20) | Telepon pengirim |
| recipient_name | varchar(100) | Nama penerima |
| recipient_address | text | Alamat penerima |
| recipient_phone | varchar(20) | Telepon penerima |
| weight | decimal(10,2) | Berat barang |
| description | text | Deskripsi barang |
| status | enum | Status pengiriman |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

### Status Pengiriman

- `pending`: Menunggu
- `in_transit`: Dalam Pengiriman
- `delivered`: Terkirim

## Replikasi Database

Aplikasi ini menggunakan sistem replikasi sederhana dengan:

1. **Event Listeners**: Setiap perubahan data di model Shipment akan otomatis mereplikasi ke database sekunder
2. **Manual Sync**: Command `php artisan db:sync` untuk sinkronisasi manual
3. **API Sync**: Endpoint `/api/sync-databases` untuk sinkronisasi via web

## Akses dari Device Lain

Untuk mengakses aplikasi dari device lain dalam jaringan yang sama:

1. Jalankan server dengan host 0.0.0.0:
   \`\`\`bash
   php artisan serve --host=0.0.0.0 --port=8000
   \`\`\`

2. Cari IP address komputer server:
   \`\`\`bash
   # Windows
   ipconfig
   
   # Linux/Mac
   ifconfig
   \`\`\`

3. Akses dari device lain menggunakan IP server:
   \`\`\`
   http://192.168.1.100:8000
   \`\`\`

## Troubleshooting

### Error Koneksi Database

1. Pastikan MySQL dan PostgreSQL sudah berjalan
2. Cek konfigurasi di file `.env`
3. Pastikan database sudah dibuat
4. Cek username dan password database

### Error Permission

\`\`\`bash
# Set permission untuk storage
chmod -R 775 storage
chmod -R 775 bootstrap/cache
\`\`\`

### Error Composer

\`\`\`bash
# Update composer
composer self-update

# Clear cache
composer clear-cache
\`\`\`

## Pengembangan Lebih Lanjut

Untuk pengembangan production, pertimbangkan:

1. **Message Queue**: Gunakan Redis/RabbitMQ untuk replikasi yang lebih reliable
2. **Change Data Capture**: Implementasi CDC tools seperti Debezium
3. **Load Balancer**: Untuk distribusi traffic
4. **Monitoring**: Setup monitoring untuk replikasi database
5. **Backup Strategy**: Strategi backup untuk kedua database

## Lisensi

MIT License
