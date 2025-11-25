<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // <-- ضروري لدعم UUID
    public $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id', // <-- إضافة 'id'
        'order_id',
        'laptop_id',
        'quantity',
        'price_at_order',
    ];

    // علاقة: العنصر تابع لطلب
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // علاقة: العنصر تابع لجهاز
    public function laptop()
    {
        return $this->belongsTo(Laptop::class, 'laptop_id');
    }
}
