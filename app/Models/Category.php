<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'category_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'status'
    ];
    
    /**
     * Get the products for the category (legacy support).
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    
    /**
     * Get the products for this category through the product_categories pivot table.
     */
    public function productsMany()
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id')
                    ->withTimestamps();
    }
}
