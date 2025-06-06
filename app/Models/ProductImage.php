<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    protected $primaryKey = 'image_id';
    
    protected $table = 'product_images';
    
    protected $fillable = [
        'product_id',
        'image',
        'sort_order',
        'is_main'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
