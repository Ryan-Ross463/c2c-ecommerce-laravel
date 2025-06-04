<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wishlists';
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'wishlist_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_id'
    ];
    
    /**
     * Get the user that owns the wishlist item.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Get the product that is in the wishlist.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
