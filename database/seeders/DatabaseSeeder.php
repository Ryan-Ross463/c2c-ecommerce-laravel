<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if roles table exists, if not create it
        if (!\Schema::hasTable('roles')) {
            \DB::unprepared("
                CREATE TABLE IF NOT EXISTS `roles` (
                  `role_id` bigint unsigned NOT NULL AUTO_INCREMENT,
                  `name` varchar(50) NOT NULL,
                  `description` varchar(255) NOT NULL,
                  `created_at` timestamp NULL DEFAULT NULL,
                  `updated_at` timestamp NULL DEFAULT NULL,
                  PRIMARY KEY (`role_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        }

        // Run our seeders
        $this->call([
            RolesSeeder::class,
            CategoriesSeeder::class
        ]);
        
        // Create a test admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@c2c.com',
            'password' => bcrypt('Admin@123'),
            'role_id' => 3, // Admin role
            'status' => 'active',
            'email_verified' => true,
            'phone' => '1234567890'
        ]);
        
        // Create a test buyer user
        User::factory()->create([
            'name' => 'Sample Buyer',
            'email' => 'buyer@c2c.com',
            'password' => bcrypt('Password123!'),
            'role_id' => 1, // Buyer role
            'status' => 'active',
            'email_verified' => true,
            'phone' => '0987654321',
            'city' => 'New York'
        ]);
        
        // Create a test seller user
        User::factory()->create([
            'name' => 'Sample Seller',
            'email' => 'seller@c2c.com',
            'password' => bcrypt('Password123!'),
            'role_id' => 2, // Seller role
            'status' => 'active',
            'email_verified' => true,
            'phone' => '1122334455',
            'business_name' => 'Sample Store',
            'city' => 'Los Angeles'
        ]);
    }
}
