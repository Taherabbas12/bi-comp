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
        $month = $request->get('month', now()->format('Y-m'));
        $start = Carbon::parse($month)->startOfMonth();
        $end   = Carbon::parse($month)->endOfMonth();

        $usersCount = User::count();

        $attendanceByDay = Attendance::whereBetween('work_date', [$start, $end])
            ->selectRaw('work_date, COUNT(DISTINCT user_id) as present_count')
            ->groupBy('work_date')
            ->pluck('present_count', 'work_date');

        return view('admin.attendance.index', [
            'month' => $month,
            'start' => $start,
            'end'   => $end,
            'attendanceByDay' => $attendanceByDay,
            'usersCount' => $usersCount,
        ]);
    }
    public function day(string $date)
    {
        $day = Carbon::parse($date);

        $records = Attendance::with('user')
            ->whereDate('work_date', $day)
            ->orderBy('check_in_at')
            ->get();

        return view('admin.attendance.day', compact('day', 'records'));
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
}
