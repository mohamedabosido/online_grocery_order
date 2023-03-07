<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'discount_rate', 'discount', 'category_id'
    ];

    //Relationships

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function product_details()
    {
        return $this->hasMany(ProductDetail::class);
    }
    public function product_rates()
    {
        return $this->hasMany(ProductRate::class);
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function carts()
    {
        return $this->hasMany(Favorite::class);
    }
}
