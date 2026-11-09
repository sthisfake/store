<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatus;


class Order extends Model
{

    protected $fillable = [
        'user_id',
        'address_id',
        'order_number',
        'subtotal',
        'discount_amount',
        'shipping_cost',
        'tax_amount',
        'total_amount',
        'status',
        'notes',
        'paid_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function address(){
        return $this->belongsTo(Address::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    public function payment(){
        return $this->hasOne(Payment::class);
    }
}
