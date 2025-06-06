<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Shipment;

class SyncDatabases extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:sync {--direction=both : Direction of sync (mysql-to-postgres, postgres-to-mysql, both)}';

    /**
     * The console command description.
     */
    protected $description = 'Sinkronisasi data antara MySQL dan PostgreSQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $direction = $this->option('direction');
        
        $this->info('Memulai sinkronisasi database...');
        
        try {
            switch ($direction) {
                case 'mysql-to-postgres':
                    $this->syncMysqlToPostgres();
                    break;
                case 'postgres-to-mysql':
                    $this->syncPostgresToMysql();
                    break;
                case 'both':
                default:
                    $this->syncMysqlToPostgres();
                    $this->syncPostgresToMysql();
                    break;
            }
            
            $this->info('✅ Sinkronisasi database berhasil!');
        } catch (\Exception $e) {
            $this->error('❌ Gagal melakukan sinkronisasi: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    private function syncMysqlToPostgres()
    {
        $this->info('Sinkronisasi MySQL → PostgreSQL...');
        
        $mysqlShipments = DB::connection('mysql')->table('shipments')->get();
        $syncedCount = 0;
        
        foreach ($mysqlShipments as $shipment) {
            DB::connection('pgsql')->table('shipments')->updateOrInsert(
                ['id' => $shipment->id],
                (array) $shipment
            );
            $syncedCount++;
        }
        
        $this->info("✅ {$syncedCount} record berhasil disinkronkan ke PostgreSQL");
    }
    
    private function syncPostgresToMysql()
    {
        $this->info('Sinkronisasi PostgreSQL → MySQL...');
        
        $postgresShipments = DB::connection('pgsql')->table('shipments')->get();
        $syncedCount = 0;
        
        foreach ($postgresShipments as $shipment) {
            DB::connection('mysql')->table('shipments')->updateOrInsert(
                ['id' => $shipment->id],
                (array) $shipment
            );
            $syncedCount++;
        }
        
        $this->info("✅ {$syncedCount} record berhasil disinkronkan ke MySQL");
    }
}
