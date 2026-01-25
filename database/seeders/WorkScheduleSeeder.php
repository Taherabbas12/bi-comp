<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WorkScheduleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // الجدول الافتراضي: من الاثنين إلى الجمعة (8:00 - 17:00)
        // السبت والأحد عطلة
        $defaultSchedule = [
            1 => ['check_in' => '08:00', 'check_out' => '17:00', 'hours' => 8, 'day_off' => false], // Monday
            2 => ['check_in' => '08:00', 'check_out' => '17:00', 'hours' => 8, 'day_off' => false], // Tuesday
            3 => ['check_in' => '08:00', 'check_out' => '17:00', 'hours' => 8, 'day_off' => false], // Wednesday
            4 => ['check_in' => '08:00', 'check_out' => '17:00', 'hours' => 8, 'day_off' => false], // Thursday
            5 => ['check_in' => '08:00', 'check_out' => '17:00', 'hours' => 8, 'day_off' => false], // Friday
            6 => ['check_in' => null, 'check_out' => null, 'hours' => 0, 'day_off' => true],        // Saturday
            7 => ['check_in' => null, 'check_out' => null, 'hours' => 0, 'day_off' => true],        // Sunday
        ];

        // إضافة جدول عمل لكل موظف
        $users = User::all();
        foreach ($users as $user) {
            // تحقق من عدم وجود جدول عمل بالفعل
            if ($user->workSchedules()->count() === 0) {
                foreach ($defaultSchedule as $dayOfWeek => $schedule) {
                    WorkSchedule::create([
                        'id' => Str::uuid(),
                        'user_id' => $user->id,
                        'day_of_week' => $dayOfWeek,
                        'official_check_in' => $schedule['check_in'],
                        'official_check_out' => $schedule['check_out'],
                        'working_hours' => $schedule['hours'],
                        'is_day_off' => $schedule['day_off'],
                    ]);
                }
            }
        }
    }
}
