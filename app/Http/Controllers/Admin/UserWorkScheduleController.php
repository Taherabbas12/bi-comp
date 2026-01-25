<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserWorkSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserWorkScheduleController extends Controller
{
    // عرض صفحة تعديل جدول العمل
    public function edit(User $user)
    {
        // الحصول على جدول العمل للموظف (أو إنشاء جداول افتراضية)
        $schedules = $user->workSchedules()
            ->orderBy('day_of_week')
            ->get();

        // إذا لم تكن هناك جداول، نقوم بإنشاء جداول افتراضية
        if ($schedules->isEmpty()) {
            for ($day = 1; $day <= 7; $day++) {
                UserWorkSchedule::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'day_of_week' => $day,
                    'check_in' => '09:00:00',
                    'check_out' => '17:00:00',
                    'is_day_off' => $day == 5, // الجمعة عطلة افتراضياً
                ]);
            }
            $schedules = $user->workSchedules()->orderBy('day_of_week')->get();
        }

        $days = [
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
            7 => 'الأحد',
        ];

        return view('admin.user-work-schedules.edit', compact('user', 'schedules', 'days'));
    }

    // تحديث جدول العمل
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|integer|between:1,7',
            'schedules.*.is_day_off' => 'nullable|boolean',
            'schedules.*.check_in' => 'nullable|date_format:H:i',
            'schedules.*.check_out' => 'nullable|date_format:H:i',
        ]);

        foreach ($validated['schedules'] as $schedule) {
            $day = $schedule['day_of_week'];
            $isDayOff = (bool) ($schedule['is_day_off'] ?? false);

            UserWorkSchedule::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'day_of_week' => $day,
                ],
                [
                    'is_day_off' => $isDayOff,
                    'check_in' => $isDayOff ? null : ($schedule['check_in'] ?? '09:00:00'),
                    'check_out' => $isDayOff ? null : ($schedule['check_out'] ?? '17:00:00'),
                ]
            );
        }

        return redirect()->back()->with('success', 'تم تحديث جدول العمل بنجاح');
    }
}
