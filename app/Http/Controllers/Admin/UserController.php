<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
   
    public function manageUsers(Request $request)
    {
      
        $user_id = session('user_id');
        $admin = DB::table('users')->where('user_id', $user_id)->first();
          $search = $request->input('search');
        $role_filter = $request->input('role');
        $perPage = 10;
        $page = (int) $request->input('page', 1);  
        
        try {
            $query = DB::table('users')
                ->select('users.*', 'roles.name as role_name')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.role_id');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('users.name', 'LIKE', '%' . $search . '%')
                      ->orWhere('users.email', 'LIKE', '%' . $search . '%');
                });
            }
            
            if ($role_filter) {
                $query->where('roles.name', $role_filter);
            }
            
            $countQuery = clone $query;
            
            $totalUsers = $countQuery->count();
            
            $query->orderBy('users.user_id', 'desc');
            
            $users = $query->skip(($page - 1) * $perPage)
                          ->take($perPage)
                          ->get();
            
            $totalPages = ceil($totalUsers / $perPage);
            
        } catch (\Exception $e) {
            $query = DB::table('users');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%')
                      ->orWhere('email', 'LIKE', '%' . $search . '%');
                });
            }
            
            $countQuery = clone $query;
            $totalUsers = $countQuery->count();
            
            $query->orderBy('user_id', 'desc');
              $users = $query->skip(($page - 1) * $perPage)
                          ->take($perPage)
                          ->get();
            
            $totalPages = (int) ceil($totalUsers / $perPage);
        }
        
        $roles = DB::table('roles')->get();
        
        $page_title = "Manage Users";
          return response()->view('admin.users.manage_users', [
            'page_title' => $page_title,
            'admin' => $admin,
            'users' => $users,
            'search' => $search,
            'role_filter' => $role_filter,
            'current_page' => (int) $page,
            'total_pages' => (int) $totalPages,
            'total_users' => (int) $totalUsers,
            'roles' => $roles,
            'per_page' => (int) $perPage
        ]);
    }
    
    public function viewUser($id)
    {
        $user_id = session('user_id');
        $admin = DB::table('users')->where('user_id', $user_id)->first();
        
        $user = DB::table('users')
            ->select('users.*', 'roles.name as role_name')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.role_id')
            ->where('users.user_id', $id)
            ->first();
        
        if (!$user) {
            return redirect('/admin/users/manage_users')->with('error', 'User not found');
        }
        
        $page_title = "View User: " . $user->name;
        
        return response()->view('admin.users.view_user', [
            'page_title' => $page_title,
            'admin' => $admin,
            'user' => $user
        ]);
    }
    
    public function createAdmin()
    {
        $user_id = session('user_id');
        $admin = DB::table('users')->where('user_id', $user_id)->first();
        
        $page_title = "Create Admin User";
        
        return response()->view('admin.users.create_admin', [
            'page_title' => $page_title,
            'admin' => $admin
        ]);
    }
    
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect('/admin/users/manage_users')->with('success', 'Admin user created successfully');
    }
    
  public function deleteUser(Request $request, $id)
    {
        if ($id == session('user_id')) {
            return redirect('/admin/users/manage_users')->with('error', 'You cannot delete your own account');
        }
        
        $user = DB::table('users')->where('user_id', $id)->first();
        $userName = $user ? $user->name : 'User';
        
        DB::table('users')->where('user_id', $id)->delete();
        
        $returnTo = $request->input('return_to');
        $redirectPath = '/admin/users/manage_users' . ($returnTo ? $returnTo : '');
        
        return redirect($redirectPath)->with('success', "User '{$userName}' has been deleted successfully");
    }

    public function editUser($id)
    {
        $user_id = session('user_id');
        $admin = DB::table('users')->where('user_id', $user_id)->first();
        
        $user = DB::table('users')->where('user_id', $id)->first();
        
        if (!$user) {
            return redirect('/admin/users/manage_users')->with('error', 'User not found');
        }
        
        $roles = DB::table('roles')->orderBy('name')->get();
        
        $page_title = "Edit User: " . $user->name;
        
        return response()->view('admin.users.edit_user', [
            'page_title' => $page_title,
            'admin' => $admin,
            'user' => $user,
            'roles' => $roles
        ]);
    }
    
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id.',user_id',
            'role_id' => 'required|numeric|exists:roles,role_id',
        ]);
        
        $user = DB::table('users')->where('user_id', $id)->first();
        
        if (!$user) {
            return redirect('/admin/users/manage_users')->with('error', 'User not found');
        }
        
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'updated_at' => now()
        ];
        
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            
            $updateData['password'] = Hash::make($request->password);
        }
        
        DB::table('users')->where('user_id', $id)->update($updateData);
        
        return redirect('/admin/users/manage_users')->with('success', 'User updated successfully');
    }
}
