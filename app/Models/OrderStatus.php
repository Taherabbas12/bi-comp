<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    // <-- ضروري لدعم UUID
    public $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id', // <-- إضافة 'id'
        'name',
        'display_name',
    ];

    // علاقة: الحالة تملك العديد من الطلبات
    public function orders()
    {
        return $this->hasMany(Order::class, 'order_status_id');
    }
}
