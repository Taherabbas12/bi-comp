<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkScheduleController extends Controller
{
    /**
     * عرض أوقات العمل الرسمية لموظف
     */
    public function edit(User $user)
    {
        // تحميل جداول العمل
        $workSchedules = WorkSchedule::where('user_id', $user->id)
            ->orderBy('day_of_week')
            ->get();

        // إذا لم توجد جداول، إنشاؤها
        if ($workSchedules->isEmpty()) {
            for ($day = 1; $day <= 7; $day++) {
                WorkSchedule::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'day_of_week' => $day,
                    'official_check_in' => '08:00',
                    'official_check_out' => '17:00',
                    'working_hours' => 8,
                    'is_day_off' => in_array($day, [5, 6]) ? true : false, // الجمعة والسبت عطل
                ]);
            }
            $workSchedules = WorkSchedule::where('user_id', $user->id)
                ->orderBy('day_of_week')
                ->get();
        }

        return view('admin.work-schedules.edit', compact('user', 'workSchedules'));
    }

    /**
     * تحديث أوقات العمل الرسمية
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'schedules' => 'required|array',
            'schedules.*.is_day_off' => 'nullable|boolean',
            'schedules.*.official_check_in' => 'nullable|date_format:H:i',
            'schedules.*.official_check_out' => 'nullable|date_format:H:i',
            'schedules.*.working_hours' => 'nullable|numeric',
        ]);

        // تحديث كل جدول عمل
        foreach ($validated['schedules'] as $scheduleId => $data) {
            $schedule = WorkSchedule::find($scheduleId);

            if ($schedule && $schedule->user_id === $user->id) {
                // حساب ساعات العمل إذا لم تكن عطلة
                if (!isset($data['is_day_off']) || !$data['is_day_off']) {
                    if ($data['official_check_in'] && $data['official_check_out']) {
                        $checkIn = Carbon::createFromFormat('H:i', $data['official_check_in']);
                        $checkOut = Carbon::createFromFormat('H:i', $data['official_check_out']);
                        $hours = $checkOut->diffInMinutes($checkIn) / 60;
                        $data['working_hours'] = $hours;
                    }
                    $data['is_day_off'] = false;
                } else {
                    $data['is_day_off'] = true;
                }

                $schedule->update($data);
            }
        }

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'تم تحديث أوقات العمل بنجاح');
    }

    /**
     * إنشاء جداول عمل افتراضية لموظف جديد
     */
    public function createDefaults(User $user)
    {
        // تحقق من عدم وجود جداول بالفعل
        if (WorkSchedule::where('user_id', $user->id)->exists()) {
            return;
        }

        // الجدول الافتراضي: الأحد-الخميس 8صباحاً-5مساءً، الجمعة والسبت عطل
        $defaultSchedule = [
            ['day' => 1, 'check_in' => '08:00', 'check_out' => '17:00', 'hours' => 8, 'off' => false],   // الاثنين
            ['day' => 2, 'check_in' => '08:00', 'check_out' => '17:00', 'hours' => 8, 'off' => false],   // الثلاثاء
            ['day' => 3, 'check_in' => '08:00', 'check_out' => '17:00', 'hours' => 8, 'off' => false],   // الأربعاء
            ['day' => 4, 'check_in' => '08:00', 'check_out' => '17:00', 'hours' => 8, 'off' => false],   // الخميس
            ['day' => 5, 'check_in' => null, 'check_out' => null, 'hours' => 0, 'off' => true],          // الجمعة
            ['day' => 6, 'check_in' => null, 'check_out' => null, 'hours' => 0, 'off' => true],          // السبت
            ['day' => 7, 'check_in' => '08:00', 'check_out' => '17:00', 'hours' => 8, 'off' => false],   // الأحد
        ];

        foreach ($defaultSchedule as $schedule) {
            WorkSchedule::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'day_of_week' => $schedule['day'],
                'official_check_in' => $schedule['check_in'],
                'official_check_out' => $schedule['check_out'],
                'working_hours' => $schedule['hours'],
                'is_day_off' => $schedule['off'],
            ]);
        }
    }
}
