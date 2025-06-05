<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user and role for C2C Ecommerce';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Creating admin user and role...');
            
            // Check if admin role exists, create if not
            $adminRole = DB::table('roles')->where('role_id', 3)->first();
            if (!$adminRole) {
                DB::table('roles')->insert([
                    'role_id' => 3,
                    'name' => 'admin',
                    'description' => 'Administrator with full access',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->info('✅ Admin role created successfully.');
            } else {
                $this->info('ℹ️  Admin role already exists.');
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
                
                $this->info('✅ Admin user created successfully!');
                $this->info('📧 Email: admin@c2cecommerce.com');
                $this->info('🔐 Password: Admin123!@#');
                $this->warn('⚠️  Please change the default password after first login.');
                
                return 0;
            } else {
                $this->info('ℹ️  Admin user already exists.');
                $this->info('📧 Email: admin@c2cecommerce.com');
                $this->info('🔐 Password: Admin123!@#');
                
                return 0;
            }
        } catch (\Exception $e) {
            $this->error('❌ Error creating admin user: ' . $e->getMessage());
            return 1;
        }
    }
}
