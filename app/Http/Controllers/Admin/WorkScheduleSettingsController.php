<?php

namespace App\Http\Controllers\Admin;

use App\Models\WorkScheduleSettings;
use Illuminate\Http\Request;

class WorkScheduleSettingsController extends Controller
{
    // عرض صفحة الإعدادات
    public function edit()
    {
        $settings = WorkScheduleSettings::current();

        $days = [
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
            7 => 'الأحد',
        ];

        return view('admin.work-schedule-settings.edit', compact('settings', 'days'));
    }

    // تحديث الإعدادات
    public function update(Request $request)
    {
        $validated = $request->validate([
            'official_check_in' => 'required|date_format:H:i',
            'official_check_out' => 'required|date_format:H:i|after:official_check_in',
            'working_hours' => 'required|numeric|min:1|max:24',
            'working_days_per_week' => 'required|integer|min:1|max:7',
            'default_day_off' => 'required|integer|between:1,7',
        ]);

        $settings = WorkScheduleSettings::current();
        $settings->update($validated);

        return redirect()->back()->with('success', 'تم تحديث إعدادات أوقات العمل بنجاح');
    }
}
