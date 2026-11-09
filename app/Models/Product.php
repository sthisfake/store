<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ProductStatus;
use App\Enums\ProductType;

class Product extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'status',
        'product_type',
        'stock_quantity',
        'track_inventory',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'status' => ProductStatus::class,
            'product_type' => ProductType::class,
            'stock_quantity' => 'integer',
            'track_inventory' => 'boolean',
        ];
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    public function cartProducts(){
        return $this->hasMany(CartProduct::class);
    }

    public function productImages(){
        return $this->hasMany(ProductImage::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }
}
