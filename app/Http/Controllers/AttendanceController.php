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
            'qr_code'=>'required',
            'lat'=>'required|numeric',
            'lng'=>'required|numeric'
        ]);

        $qr = AttendanceQrCode::where('code',$request->qr_code)
            ->where('is_active',true)->first();

        if(!$qr){
            return response()->json(['status'=>false,'message'=>'QR غير صالح'],422);
        }

        $user = Auth::user();
        $now  = now();

        $distance = round($this->distanceInMeters(
            32.4625278,44.3990550,$request->lat,$request->lng
        ));

        if($distance > 12){
            return response()->json([
                'status'=>false,
                'message'=>'❌ خارج الشركة',
                'distance'=>$distance
            ],403);
        }

        // 检查是否有未关闭的会话
        $existingOpenSession = Attendance::where('user_id',$user->id)->whereNull('check_out_at')->first();
        if($existingOpenSession){
            // 如果存在未关闭的会话，根据需求决定是否允许新的签到
            // 这里返回错误，表示不能重复签到
            return response()->json(['status'=>false,'message'=>'جلسة مفتوحة موجودة بالفعل'],422);
        }

        $workDate = $now->hour < 3
            ? $now->copy()->subDay()->toDateString()
            : $now->toDateString();

        Attendance::create([
            'id'=>Str::uuid(),
            'user_id'=>$user->id,
            'work_date'=>$workDate,
            'check_in_at'=>$now,
            'lat'=>$request->lat,
            'lng'=>$request->lng,
            'distance_meters'=>$distance,
            'is_inside_office'=>true,
            'source'=>'qr'
        ]);

        return response()->json(['status'=>true,'message'=>'✅ تم تسجيل الحضور']);
    }

    /* ===================== CHECK OUT ===================== */
    public function checkOutByQr(Request $request)
    {
        $request->validate([
            'qr_code'=>'required',
            'lat'=>'required|numeric',
            'lng'=>'required|numeric'
        ]);

        $user = Auth::user();
        $now  = now();

        $attendance = Attendance::where('user_id',$user->id)
            ->whereNull('check_out_at')
            ->orderByDesc('check_in_at') // 确保获取最新的未签退记录
            ->first();

        if(!$attendance){
            return response()->json(['status'=>false,'message'=>'لا توجد جلسة مفتوحة'],422);
        }

        if($attendance->check_in_at->diffInMinutes($now) < 30){
            return response()->json(['status'=>false,'message'=>'❌ الحد الأدنى 30 دقيقة'],422);
        }

        $attendance->update([
            'check_out_at'=>$now,
            'lat'=>$request->lat,
            'lng'=>$request->lng
        ]);

        return response()->json(['status'=>true,'message'=>'🚪 تم تسجيل الانصراف']);
    }

    /* ===================== HANDLE FORGOTTEN SESSION ===================== */
    public function handleForgottenSession(Request $request)
    {
        $user = Auth::user();
        $now = now();

        // 查找最旧的未签退记录
        $openSession = Attendance::where('user_id', $user->id)
            ->whereNull('check_out_at')
            ->orderByAsc('check_in_at') // 获取最早开始的那个
            ->first();

        if (!$openSession) {
             return response()->json(['status' => false, 'message' => 'لا توجد جلسات مفتوحة لمعالجتها'], 404);
        }

        // 检查是否是遗忘的会话（签到时间早于今天）
        $sessionStartDate = $openSession->check_in_at->toDateString();
        $today = $now->toDateString();
        if ($sessionStartDate >= $today) {
             return response()->json(['status' => false, 'message' => 'الجلسة المفتوحة الحالية لا يمكن معالجتها كجلسة منسيّة'], 400);
        }

        // 使用当前时间或前一天晚上11点作为签退时间 (可以根据业务需求调整)
        // 这里使用当前时间作为示例
        $checkoutTime = $now;
        // 或者使用前一天晚上11点
        // $checkoutTime = $openSession->check_in_at->copy()->endOfDay(); // 这可能需要调整逻辑以确保不超过实际日期

        $openSession->update([
            'check_out_at' => $checkoutTime,
            // 可选：更新签退位置为当前位置（如果可用）
            // 'lat' => $request->lat ?? $openSession->lat,
            // 'lng' => $request->lng ?? $openSession->lng,
        ]);

        // 可选：创建一条新的记录来标记这次手动处理？取决于具体需求。
        // 例如，可以更新 work_date 或添加备注。

        return response()->json(['status' => true, 'message' => '✅ تم إغلاق الجلسة المنسية بنجاح']);
    }


    /* ===================== DASHBOARD ===================== */
  public function dashboard(Request $request)
    {
        $userId = Auth::id();
        $month  = $request->input('month', now()->format('Y-m'));

        // Parse the selected month (e.g., '2025-12')
        $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        // Define period: 5th of current month to 4th of next month
        $periodStart  = $currentMonth->copy()->day(5);
        $periodEnd    = $currentMonth->copy()->addMonth()->day(4);

        $startOfMonth = $periodStart->copy()->startOfWeek();
        $endOfMonth   = $periodEnd->copy()->endOfWeek();

        $dailyHours = [];
        $monthlyTotalHours = 0;
        $day = $startOfMonth->copy();

        while($day <= $endOfMonth){
            $date = $day->toDateString();
            $isInPeriod = $day->between($periodStart, $periodEnd);

            $dailyHours[$date] = [
                'total' => 0,
                'isCurrentMonth' => $isInPeriod,
                'hasAttendance' => false,
                'distance' => null
            ];

            if($isInPeriod){
                $records = Attendance::where('user_id', $userId)
                    ->where('work_date', $date)->get();

                $dayTotal = 0;
                $lastDistance = null;

                foreach($records as $r){
                    if($r->check_in_at && $r->check_out_at){
                        $dayTotal += $r->check_in_at->floatDiffInHours($r->check_out_at);
                    }
                    if($r->distance_meters){
                        $lastDistance = $r->distance_meters;
                    }
                }

                if($dayTotal > 0){
                    $dailyHours[$date]['total'] = $dayTotal;
                    $dailyHours[$date]['hasAttendance'] = true;
                    $dailyHours[$date]['distance'] = $lastDistance;
                    $monthlyTotalHours += $dayTotal;
                }
            }

            $day->addDay();
        }

        $openSessions = Attendance::where('user_id', $userId)
            ->whereNull('check_out_at')->get();

        // Check for forgotten session
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

        // Pass currentMonth to the view for the selector
        return view('employee.attendance.dashboard', compact(
            'currentMonth', 'periodStart', 'periodEnd',
            'startOfMonth', 'endOfMonth',
            'dailyHours', 'monthlyTotalHours',
            'openSessions', 'forgottenSession',
            'daysPresent', 'daysAbsent'
        ));
    }
    /* ===================== DISTANCE ===================== */
    private function distanceInMeters($lat1,$lon1,$lat2,$lon2):float
    {
        $earth=6371000;
        $dLat=deg2rad($lat2-$lat1);
        $dLon=deg2rad($lon2-$lon1);

        $a = sin($dLat/2)**2 +
            cos(deg2rad($lat1))*cos(deg2rad($lat2))*
            sin($dLon/2)**2;

        return $earth*(2*atan2(sqrt($a),sqrt(1-$a)));
    }
}
