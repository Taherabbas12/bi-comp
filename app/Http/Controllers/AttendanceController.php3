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
    /*
    |--------------------------------------------------------------------------
    | تسجيل الحضور عبر QR (إرجاع JSON فقط)
    |--------------------------------------------------------------------------
    */
    public function checkInByQr(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'lat'     => 'nullable|numeric',
            'lng'     => 'nullable|numeric',
        ]);

        // التحقق من صحة رمز QR
        $qr = AttendanceQrCode::where('code', $request->qr_code)
            ->where('is_active', true)
            ->first();

        if (! $qr) {
            return response()->json([
                'status'  => false,
                'message' => 'رمز QR غير صالح.',
            ], 422);
        }

        // التحقق من الموقع
        if (! $this->isInsideOffice($request)) {
            return response()->json([
                'status'  => false,
                'message' => 'يجب أن تكون داخل الشركة لتسجيل الحضور.',
            ], 403);
        }

        $user  = Auth::user();
        $today = Carbon::today();

        // التحقق من عدم وجود جلسة مفتوحة
        $open = Attendance::where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->whereNull('check_out_at')
            ->first();

        if ($open) {
            return response()->json([
                'status'  => false,
                'message' => 'لديك جلسة حضور مفتوحة، يجب تسجيل الانصراف أولاً.',
            ], 422);
        }

        // إنشاء جلسة حضور جديدة
        Attendance::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'work_date' => $today,
            'check_in_at' => now(),
            'lat' => $request->lat,
            'lng' => $request->lng,
            'is_inside_office' => true,
            'source' => 'qr',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'تم تسجيل الحضور بنجاح.',
        ]);
    }



    /*
    |--------------------------------------------------------------------------
    | تسجيل الانصراف عبر QR
    |--------------------------------------------------------------------------
    */
    public function checkOutByQr(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string'
        ]);

        $qr = AttendanceQrCode::where('code', $request->qr_code)
            ->where('is_active', true)
            ->first();

        if (! $qr) {
            return response()->json([
                'status'  => false,
                'message' => 'رمز QR غير صالح.',
            ], 422);
        }

        $user  = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->whereNull('check_out_at')
            ->first();

        if (! $attendance) {
            return response()->json([
                'status'  => false,
                'message' => 'لا يوجد حضور مفتوح لتسجيل الانصراف.',
            ], 422);
        }

        $attendance->update([
            'check_out_at' => now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'تم تسجيل الانصراف بنجاح.',
        ]);
    }



    /*
    |--------------------------------------------------------------------------
    | صفحة سجل الحضور
    |--------------------------------------------------------------------------
    */
    public function dashboard()
    {
        $user  = Auth::user();
        $today = Carbon::today();

        $sessions = Attendance::where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->get();

        $totalMinutes = $sessions->sum(function ($s) {
            if (!$s->check_in_at || !$s->check_out_at) return 0;
            return $s->check_in_at->diffInMinutes($s->check_out_at);
        });

        return view('employee.attendance.dashboard', [
            'sessions'     => $sessions,
            'totalMinutes' => $totalMinutes,
            'totalHours'   => round($totalMinutes / 60, 2),
        ]);
    }



    /*
    |--------------------------------------------------------------------------
    | التحقق من الموقع الجغرافي
    |--------------------------------------------------------------------------
    */
    private function isInsideOffice(Request $request): bool
    {
        // إحداثيات الشركة الدقيقة
        $officeLat = 32.4625278;
        $officeLng = 44.3990550;
        $maxDistance = 120; // متر

        if (! $request->lat || ! $request->lng)
            return false;

        $distance = $this->distanceInMeters(
            $officeLat,
            $officeLng,
            $request->lat,
            $request->lng
        );

        return $distance <= $maxDistance;
    }

    private function distanceInMeters($lat1, $lon1, $lat2, $lon2): float
    {
        $earth = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earth * $c;
    }
}