<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    protected $primaryKey = 'cart_id';
    
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'price',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
