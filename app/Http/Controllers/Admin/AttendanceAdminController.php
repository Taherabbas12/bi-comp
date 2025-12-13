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
        $users = User::orderBy('name')->get();

        $query = Attendance::with('user')->orderBy('work_date', 'desc');

        // فلترة الموظف
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // فلترة التاريخ
        if ($request->from_date) {
            $query->whereDate('work_date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('work_date', '<=', $request->to_date);
        }

        $records = $query->paginate(20);

        return view('admin.attendance.index', compact('records', 'users'));
    }



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