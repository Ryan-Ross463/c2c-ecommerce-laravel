<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate the roles table to avoid duplicates
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        \DB::table('roles')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        Role::create([
            'role_id' => 1,
            'name' => 'buyer',
            'description' => 'Regular user who can buy products'
        ]);
        
        Role::create([
            'role_id' => 2,
            'name' => 'seller',
            'description' => 'User who can sell products'
        ]);
        
        Role::create([
            'role_id' => 3,
            'name' => 'admin',
            'description' => 'Administrator with full access'
        ]);
    }
}
