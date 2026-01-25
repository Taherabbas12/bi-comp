<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkSchedule extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'day_of_week',
        'official_check_in',
        'official_check_out',
        'working_hours',
        'is_day_off',
    ];

    protected $casts = [
        'working_hours' => 'decimal:2',
        'is_day_off' => 'boolean',
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
