<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    protected $primaryKey = 'category_id';
    
    protected $fillable = [
        'name',
        'description',
        'image',
        'status'
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    
    public function productsMany()
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id')
                    ->withTimestamps();
    }
}
