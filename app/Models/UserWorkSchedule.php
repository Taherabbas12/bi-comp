<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserWorkSchedule extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'user_work_schedules';

    protected $fillable = [
        'id',
        'user_id',
        'day_of_week',
        'check_in',
        'check_out',
        'is_day_off',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
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

    // حساب ساعات العمل
    public function getWorkingHoursAttribute()
    {
        if ($this->is_day_off || !$this->check_in || !$this->check_out) {
            return 0;
        }

        $checkIn = \Carbon\Carbon::createFromFormat('H:i:s', $this->check_in);
        $checkOut = \Carbon\Carbon::createFromFormat('H:i:s', $this->check_out);

        return $checkOut->diffInHours($checkIn);
    }
}
