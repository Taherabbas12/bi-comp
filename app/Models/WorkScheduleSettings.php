<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkScheduleSettings extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'official_check_in',
        'official_check_out',
        'working_hours',
        'working_days_per_week',
        'default_day_off',
    ];

    protected $casts = [
        'working_hours' => 'decimal:2',
        'working_days_per_week' => 'integer',
        'default_day_off' => 'integer',
    ];

    // الحصول على اسم يوم الإجازة الافتراضي
    public function getDefaultDayOffNameAttribute()
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
        return $days[$this->default_day_off] ?? 'غير معروف';
    }

    // الحصول على الإعدادات الحالية (singleton pattern)
    public static function current()
    {
        return self::first() ?? self::create([
            'official_check_in' => '09:00:00',
            'official_check_out' => '17:00:00',
            'working_hours' => 8,
            'working_days_per_week' => 6,
            'default_day_off' => 5, // الجمعة
        ]);
    }
}
