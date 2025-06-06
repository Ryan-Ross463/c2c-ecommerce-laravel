<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    protected $primaryKey = 'order_id';
    
    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'total_amount',
        'shipping_address',
        'billing_address',
        'payment_method',
        'payment_status',
        'shipping_method',
        'notes'
    ];
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public static function generateOrderNumber()
    {
        $prefix = 'ORD-';
        $randomPart = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        $datePart = date('Ymd');
        
        return $prefix . $datePart . '-' . $randomPart;
    }
}
