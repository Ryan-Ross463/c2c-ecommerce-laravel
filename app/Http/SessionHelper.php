<?php

namespace App\Http;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class SessionHelper
{
   
    public static function initializeSessionsTable()
    {
        try {
            if (!Schema::hasTable('sessions')) {
                self::createSessionsTable();
                Log::info('Sessions table created successfully.');
            }
            
            $columns = Schema::getColumnListing('sessions');
            $requiredColumns = ['id', 'user_id', 'ip_address', 'user_agent', 'payload', 'last_activity'];
            
            $missingColumns = array_diff($requiredColumns, $columns);
            if (!empty($missingColumns)) {
                Log::warning('Sessions table is missing columns: ' . implode(', ', $missingColumns));
               
                self::createSessionsTable();
                Log::info('Sessions table recreated with all required columns.');
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to initialize sessions table: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
    
    private static function createSessionsTable()
    {
        $schema = "
        CREATE TABLE IF NOT EXISTS `sessions` (
          `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `user_id` bigint(20) UNSIGNED DEFAULT NULL,
          `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
          `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
          `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
          `last_activity` int(11) NOT NULL,
          PRIMARY KEY (`id`),
          KEY `sessions_user_id_index` (`user_id`),
          KEY `sessions_last_activity_index` (`last_activity`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        DB::unprepared($schema);
    }
}
