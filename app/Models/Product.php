<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    protected $primaryKey = 'product_id';
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'condition_type',
        'seller_id',
        'status'
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id')
                    ->withTimestamps();
    }
    
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
