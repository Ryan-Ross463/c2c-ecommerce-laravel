<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'department',
        'profile_image',
        'status',
        'phone',
        'dob',
        'gender',
        'business_name',
        'city',
        'profile_image',
        'last_login',
        'account_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'last_login' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    
    public function hasRole($role)
    {
        return $this->role->name === $role;
    }
    
    public function isAdmin()
    {
        return $this->role_id === 3;
    }
    
    public function isSeller()
    {
        return $this->role_id === 2;
    }
    
    public function isBuyer()
    {
        return $this->role_id === 1;
    }
}
