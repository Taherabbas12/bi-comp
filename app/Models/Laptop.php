<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- إضافة الحذف الناعم

class Laptop extends Model
{
    use HasFactory, SoftDeletes; // <-- إضافة SoftDeletes

    // <-- ضروري لدعم UUID
    public $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id', // <-- إضافة 'id'
        'quantity',
        'barcode',
        'name',
        'price_numeric', // السعر كرقم (500 → 500000)
        'price_display', // السعر المعروض (500,000 د.ع)
        'brand',
        'model',
        'processor',
        'ram',
        'storage',
        'screen',
        'is_touch',
        'is_convertible',
        'gpu',
    ];

    // علاقة: الجهاز يملك العديد من عناصر الطلبات
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'laptop_id');
    }

    // دالة مساعدة لتحويل السعر من النص إلى رقم (مطابقة لدالة PHP الأصلية)
    public static function priceToNumber($priceStr)
    {
        return (int) str_replace(['د.ع', ',', ' '], '', $priceStr);
    }

    // دالة لحساب القسط الشهري (مطابقة لدالة PHP الأصلية)
    public function calculateMonthlyPayment($months, $interestRate = 1.265)
    {
        $price = $this->price_numeric; // نستخدم السعر المحفوظ كرقم
        $totalWithInterest = $price * (1 + ($interestRate * $months / 100));

        return $totalWithInterest / $months;
    }
}
