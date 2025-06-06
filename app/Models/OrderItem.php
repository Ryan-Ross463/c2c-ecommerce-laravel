<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    protected $primaryKey = 'order_item_id';
    
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'product_name',
        'seller_id'
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    
    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
