<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDayOff;
use App\Models\WorkScheduleSettings;
use Illuminate\Http\Request;

class UserDayOffController extends Controller
{
    // عرض أيام الإجازة للموظف
    public function edit(User $user)
    {
        $settings = WorkScheduleSettings::current();
        $userDayOffs = $user->dayOffs()->pluck('day_of_week')->toArray();

        $days = [
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
            7 => 'الأحد',
        ];

        return view('admin.user-day-offs.edit', compact('user', 'settings', 'userDayOffs', 'days'));
    }

    // تحديث أيام الإجازة
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:1,7',
        ]);

        // حذف أيام الإجازة القديمة
        $user->dayOffs()->delete();

        // إضافة أيام الإجازة الجديدة
        $user->dayOffs()->create([
            'day_of_week' => $validated['day_of_week'],
        ]);

        return redirect()->back()->with('success', 'تم تحديث يوم الإجازة بنجاح');
    }
}
