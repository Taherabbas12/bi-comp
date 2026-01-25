<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDayOff extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'user_day_offs';

    protected $fillable = [
        'id',
        'user_id',
        'day_of_week',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
    ];

    // العلاقة مع الموظف
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // الحصول على اسم اليوم
    public function getDayNameAttribute()
    {
        $days = [
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
            7 => 'الأحد',
        ];
        return $days[$this->day_of_week] ?? 'غير معروف';
    }
}
