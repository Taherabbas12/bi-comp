<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- إضافة الحذف الناعم

class Order extends Model
{
    use HasFactory, SoftDeletes; // <-- إضافة SoftDeletes

    // <-- ضروري لدعم UUID
    public $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id', // <-- إضافة 'id'
        'user_id', // الزبون
        'order_status_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'order_notes',
        'notes', // الحقل الجديد
        'source',
        'payment_type',
        'installment_months',
        'total_amount',
        'employee_id', // الموظف المُسند إليه الطلب
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    // علاقة: الطلب تابع لمستخدم (الزبون)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // علاقة: الطلب مرتبط بموظف (ردود)
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id'); // 'employee_id' هو اسم الحقل في جدول 'orders'
    }

    // علاقة: الطلب له حالة
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    // علاقة: الطلب يملك العديد من عناصر الطلبات
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
