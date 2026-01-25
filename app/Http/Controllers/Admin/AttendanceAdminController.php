<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceAdminController extends Controller
{
    // الصفحة الرئيسية لإدارة الحضور
    public function index(Request $request)
    {
        $month  = $request->get('month', now()->format('Y-m'));
        $userId = $request->get('user_id'); // 👈 الموظف المختار

        // 🟢 بداية الفترة: 6
        $start = Carbon::createFromFormat('Y-m', $month)
            ->day(6)
            ->startOfDay();

        // 🟢 نهاية الفترة: 5
        $end = $start->copy()
            ->addMonth()
            ->day(5)
            ->endOfDay();

        $users = User::orderBy('name')->get();

        $query = Attendance::whereBetween('work_date', [$start, $end]);

        // ✅ فلترة حسب الموظف إن وجد
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $attendanceByDay = $query
            ->selectRaw('work_date, COUNT(DISTINCT user_id) as present_count')
            ->groupBy('work_date')
            ->pluck('present_count', 'work_date');

        $usersCount = $userId ? 1 : User::count();

        // حساب إحصائيات الأسابيع للموظف المختار
        $weeklyStats = [];
        if ($userId) {
            $currentDate = $start->copy();
            $weekNumber = 1;
            
            while ($currentDate <= $end) {
                $weekStart = $currentDate->copy()->startOfWeek();
                $weekEnd = $currentDate->copy()->endOfWeek();
                
                // تأكد من أن الأسبوع ضمن نطاق الشهر
                if ($weekStart > $end) break;
                if ($weekEnd < $start) {
                    $currentDate->addWeek();
                    $weekNumber++;
                    continue;
                }
                
                $weekRecords = Attendance::where('user_id', $userId)
                    ->whereBetween('work_date', [$weekStart, $weekEnd])
                    ->get();
                
                $weekMinutes = $weekRecords->sum(fn($r) => $r->session_minutes);
                $weekDays = $weekRecords->count();
                
                $weeklyStats[] = [
                    'week_number' => $weekNumber,
                    'start' => $weekStart->format('Y-m-d'),
                    'end' => $weekEnd->format('Y-m-d'),
                    'days' => $weekDays,
                    'minutes' => $weekMinutes,
                    'hours' => intdiv($weekMinutes, 60),
                    'remaining_minutes' => $weekMinutes % 60,
                ];
                
                $currentDate->addWeek();
                $weekNumber++;
            }
        }

        return view('admin.attendance.index', compact(
            'month',
            'start',
            'end',
            'attendanceByDay',
            'usersCount',
            'users',
            'userId',
            'weeklyStats'
        ));
    }
    public function day(string $date, Request $request)
    {
        $day    = Carbon::parse($date);
        $userId = $request->get('user_id');

        $query = Attendance::with('user')
            ->whereDate('work_date', $day);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $records = $query->orderBy('check_in_at')->get();

        return view('admin.attendance.day', compact('day', 'records', 'userId'));
    }

    public function userMonthly(Request $request)
    {
        $userId = $request->get('user_id');
        abort_if(! $userId, 404);

        $month = $request->get('month', now()->format('Y-m'));

        // Period from day 6 to day 5
        $start = Carbon::createFromFormat('Y-m', $month)
            ->day(6)
            ->startOfDay();

        $end = $start->copy()
            ->addMonth()
            ->day(5)
            ->endOfDay();

        $user = User::findOrFail($userId);

        $records = Attendance::where('user_id', $userId)
            ->whereBetween('work_date', [$start, $end])
            ->orderBy('work_date')
            ->get();

        $totalMinutes = $records->sum(fn ($r) => $r->session_minutes);

        return view('admin.attendance.user-monthly', compact(
            'user',
            'records',
            'month',
            'start',
            'end',
            'totalMinutes'
        ));
    }
    // الصفحة الرئيسية لإدارة الحضور
    // public function index(Request $request)
    // {
    //     $users = User::orderBy('name')->get();

    //     $query = Attendance::with('user')->orderBy('work_date', 'desc');

    //     // فلترة الموظف
    //     if ($request->user_id) {
    //         $query->where('user_id', $request->user_id);
    //     }

    //     // فلترة التاريخ
    //     if ($request->from_date) {
    //         $query->whereDate('work_date', '>=', $request->from_date);
    //     }

    //     if ($request->to_date) {
    //         $query->whereDate('work_date', '<=', $request->to_date);
    //     }

    //     $records = $query->paginate(20);

    //     return view('admin.attendance.index', compact('records', 'users'));
    // }



    // صفحة حضور موظف واحد بالتفصيل
    public function userAttendance($id)
    {
        $user = User::findOrFail($id);

        $records = Attendance::where('user_id', $id)
            ->orderBy('work_date', 'desc')
            ->paginate(30);

        return view('admin.attendance.user', compact('user', 'records'));
    }

    // تحديث سجل الحضور
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $validated = $request->validate([
            'work_date' => 'required|date',
            'check_in_at' => 'nullable|date_format:Y-m-d H:i',
            'check_out_at' => 'nullable|date_format:Y-m-d H:i',
        ]);

        // تحديث التاريخ والأوقات
        $attendance->update([
            'work_date' => $validated['work_date'],
            'check_in_at' => $validated['check_in_at'] ? Carbon::createFromFormat('Y-m-d H:i', $validated['check_in_at']) : null,
            'check_out_at' => $validated['check_out_at'] ? Carbon::createFromFormat('Y-m-d H:i', $validated['check_out_at']) : null,
        ]);

        return back()->with('success', 'تم تحديث سجل الحضور بنجاح');
    }
}
