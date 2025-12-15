<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceQrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /* ===================== CHECK IN ===================== */
    public function checkInByQr(Request $request)
    {
        $request->validate([
            'qr_code' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ]);

        $qr = AttendanceQrCode::where('code', $request->qr_code)
            ->where('is_active', true)->first();

        if (!$qr) {
            return response()->json(['status' => false, 'message' => 'QR غير صالح'], 422);
        }

        $user = Auth::user();
        $now  = now();

        $distance = round($this->distanceInMeters(
            32.4625278,
            44.3990550,
            $request->lat,
            $request->lng
        ));

        if ($distance > 12) {
            return response()->json([
                'status' => false,
                'message' => '❌ خارج الشركة',
                'distance' => $distance
            ], 403);
        }

        // منع تسجيل حضور جديد إذا كانت هناك جلسة مفتوحة (حتى لو قديمة)
        $existingOpenSession = Attendance::where('user_id', $user->id)
            ->whereNull('check_out_at')
            ->first();

        if ($existingOpenSession) {
            return response()->json(['status' => false, 'message' => 'جلسة مفتوحة موجودة بالفعل'], 422);
        }

        $workDate = $now->hour < 3
            ? $now->copy()->subDay()->toDateString()
            : $now->toDateString();

        Attendance::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'work_date' => $workDate,
            'check_in_at' => $now,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'distance_meters' => $distance,
            'is_inside_office' => true,
            'source' => 'qr'
        ]);

        return response()->json(['status' => true, 'message' => '✅ تم تسجيل الحضور']);
    }

    /* ===================== CHECK OUT ===================== */
    public function checkOutByQr(Request $request)
    {
        $request->validate([
            'qr_code' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ]);

        $user = Auth::user();
        $now  = now();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereNull('check_out_at')
            ->orderByDesc('check_in_at')
            ->first();

        if (!$attendance) {
            return response()->json(['status' => false, 'message' => 'لا توجد جلسة مفتوحة'], 422);
        }

        if ($attendance->check_in_at->diffInMinutes($now) < 30) {
            return response()->json(['status' => false, 'message' => '❌ الحد الأدنى 30 دقيقة'], 422);
        }

        $attendance->update([
            'check_out_at' => $now,
            'lat' => $request->lat,
            'lng' => $request->lng
        ]);

        return response()->json(['status' => true, 'message' => '🚪 تم تسجيل الانصراف']);
    }

    /* ===================== HANDLE FORGOTTEN SESSION ===================== */
    public function handleForgottenSession(Request $request)
    {
        $user = Auth::user();
        $now = now();

        $openSession = Attendance::where('user_id', $user->id)
            ->whereNull('check_out_at')
            ->orderByAsc('check_in_at')
            ->first();

        if (!$openSession) {
            return response()->json(['status' => false, 'message' => 'لا توجد جلسات مفتوحة لمعالجتها'], 404);
        }

        $sessionStartDate = $openSession->check_in_at->toDateString();
        $today = $now->toDateString();

        if ($sessionStartDate >= $today) {
            return response()->json(['status' => false, 'message' => 'الجلسة المفتوحة الحالية لا يمكن معالجتها كجلسة منسيّة'], 400);
        }

        // عند الإغلاق اليدوي، ننهيها الآن
        $openSession->update([
            'check_out_at' => $now,
        ]);

        return response()->json(['status' => true, 'message' => '✅ تم إغلاق الجلسة المنسية بنجاح']);
    }

    /* ===================== DASHBOARD ===================== */
    public function dashboard(Request $request)
    {
        $userId = Auth::id();
        $month  = $request->input('month', now()->format('Y-m'));

        // Parse month
        $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $periodStart  = $currentMonth->copy()->day(5);
        $periodEnd    = $currentMonth->copy()->addMonth()->day(4);

        $startOfMonth = $periodStart->copy()->startOfWeek();
        $endOfMonth   = $periodEnd->copy()->endOfWeek();

        // 🔥 🔥 🔥 إغلاق الجلسات المفتوحة تلقائيًا بعد 8 ساعات كحد أقصى
        $expiredSessions = Attendance::where('user_id', $userId)
            ->whereNull('check_out_at')
            ->where('check_in_at', '<', now()->subHours(8))
            ->get();

        foreach ($expiredSessions as $session) {
            $autoCheckout = $session->check_in_at->copy()->addHours(8);
            // لا نسمح بأن يكون وقت الخروج في المستقبل
            if ($autoCheckout->isFuture()) {
                $autoCheckout = now();
            }
            $session->update(['check_out_at' => $autoCheckout]);
        }

        // الآن نعيد جلب الجلسات المفتوحة (بعد الإغلاق التلقائي)
        $openSessions = Attendance::where('user_id', $userId)
            ->whereNull('check_out_at')
            ->get();

        // حساب الساعات اليومية
        $dailyHours = [];
        $monthlyTotalHours = 0;
        $day = $startOfMonth->copy();

        while ($day <= $endOfMonth) {
            $date = $day->toDateString();
            $isInPeriod = $day->between($periodStart, $periodEnd);

            $dailyHours[$date] = [
                'total' => 0,
                'isCurrentMonth' => $isInPeriod,
                'hasAttendance' => false,
                'distance' => null
            ];

            if ($isInPeriod) {
                $records = Attendance::where('user_id', $userId)
                    ->where('work_date', $date)->get();

                $dayTotal = 0;
                $lastDistance = null;

                foreach ($records as $r) {
                    if ($r->check_in_at && $r->check_out_at) {
                        $dayTotal += $r->check_in_at->floatDiffInHours($r->check_out_at);
                    }
                    if ($r->distance_meters) {
                        $lastDistance = $r->distance_meters;
                    }
                }

                if ($dayTotal > 0) {
                    $dailyHours[$date]['total'] = $dayTotal;
                    $dailyHours[$date]['hasAttendance'] = true;
                    $dailyHours[$date]['distance'] = $lastDistance;
                    $monthlyTotalHours += $dayTotal;
                }
            }

            $day->addDay();
        }

        // البحث عن جلسة "منسية" (مفتوحة من يوم سابق)
        $forgottenSession = null;
        $today = now()->toDateString();
        foreach ($openSessions as $session) {
            if ($session->check_in_at->toDateString() < $today) {
                $forgottenSession = $session;
                break;
            }
        }

        $daysPresent = collect($dailyHours)->where('hasAttendance', true)->count();
        $daysAbsent  = collect($dailyHours)
            ->where('isCurrentMonth', true)
            ->where('hasAttendance', false)->count();

        // تحديد الجلسة الحالية (لعرض المؤقت الحي)
        $currentOpenSession = $openSessions->firstWhere('check_in_at', '>=', now()->startOfDay());

        return view('employee.attendance.dashboard', compact(
            'currentMonth',
            'periodStart',
            'periodEnd',
            'startOfMonth',
            'endOfMonth',
            'dailyHours',
            'monthlyTotalHours',
            'openSessions',
            'forgottenSession',
            'daysPresent',
            'daysAbsent',
            'currentOpenSession'
        ));
    }

    /* ===================== DISTANCE ===================== */
    private function distanceInMeters($lat1, $lon1, $lat2, $lon2): float
    {
        $earth = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        return $earth * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }
}
