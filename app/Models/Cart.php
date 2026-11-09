<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CartStatus;

class Cart extends Model
{   
    protected $fillable = [
        'user_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => CartStatus::class,
        ];
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cartProduct(){
        return $this->hasMany(CartProduct::class);
    }
}
