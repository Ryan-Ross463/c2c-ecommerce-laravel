<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First ensure the admin role exists
        $adminRole = DB::table('roles')->where('role_id', 3)->first();
        
        if (!$adminRole) {
            DB::table('roles')->insert([
                'role_id' => 3,
                'name' => 'admin',
                'description' => 'Administrator with full access',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Check if admin user already exists
        $existingAdmin = DB::table('users')->where('email', 'admin@c2cecommerce.com')->first();
        
        if (!$existingAdmin) {
            DB::table('users')->insert([
                'name' => 'System Administrator',
                'email' => 'admin@c2cecommerce.com',
                'password' => Hash::make('Admin123!@#'),
                'role_id' => 3,
                'account_type' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@c2cecommerce.com');
            $this->command->info('Password: Admin123!@#');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
