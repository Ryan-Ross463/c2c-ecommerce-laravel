<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TempAdminController extends Controller
{
    public function createTempAdmin(Request $request)
    {
        // Security check - only allow this on Railway or if specifically enabled
        if (!$this->isRailwayEnvironment() && !config('app.allow_temp_admin', false)) {
            abort(404, 'Not found');
        }
        
        // Check if admin already exists
        $existingAdmin = DB::table('users')->where('role_id', 3)->first();
        
        if ($existingAdmin) {
            return response()->json([
                'message' => 'Admin user already exists',
                'existing_admin' => $existingAdmin->email
            ]);
        }
        
        // Ensure admin role exists
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
        
        // Create admin user
        $adminData = [
            'name' => 'Railway Admin',
            'email' => 'admin@railway.app',
            'password' => Hash::make('RailwayAdmin123!'),
            'role_id' => 3,
            'account_type' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        DB::table('users')->insert($adminData);
        
        return response()->json([
            'message' => 'Admin user created successfully!',
            'credentials' => [
                'email' => 'admin@railway.app',
                'password' => 'RailwayAdmin123!',
                'login_url' => url('/login')
            ]
        ]);
    }
    
    private function isRailwayEnvironment()
    {
        return isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway.app') !== false;
    }
}
