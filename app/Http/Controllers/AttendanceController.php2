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

        if(Attendance::where('user_id',$user->id)->whereNull('check_out_at')->exists()){
            return response()->json(['status'=>false,'message'=>'جلسة مفتوحة'],422);
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
            ->orderByDesc('check_in_at')
            ->first();

        if(!$attendance){
            return response()->json(['status'=>false,'message'=>'لا توجد جلسة'],422);
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

    /* ===================== DASHBOARD ===================== */
    public function dashboard(Request $request)
    {
        $userId = Auth::id();
        $month  = $request->input('month',now()->format('Y-m'));

        $currentMonth = Carbon::createFromFormat('Y-m-d',$month.'-05');
        $periodStart  = $currentMonth->copy()->day(5);
        $periodEnd    = $currentMonth->copy()->addMonth()->day(4);

        $startOfMonth = $periodStart->copy()->startOfWeek();
        $endOfMonth   = $periodEnd->copy()->endOfWeek();

        $dailyHours = [];
        $monthlyTotalHours = 0;
        $day = $startOfMonth->copy();

        while($day <= $endOfMonth){

            $date = $day->toDateString();
            $isInPeriod = $day->between($periodStart,$periodEnd);

            $dailyHours[$date] = [
                'total'=>0,
                'isCurrentMonth'=>$isInPeriod,
                'hasAttendance'=>false,
                'distance'=>null
            ];

            if($isInPeriod){
                $records = Attendance::where('user_id',$userId)
                    ->where('work_date',$date)->get();

                $dayTotal = 0;
                $lastDistance = null;

                foreach($records as $r){
                    if($r->check_in_at && $r->check_out_at){
                        $dayTotal += $r->check_in_at
                            ->floatDiffInHours($r->check_out_at);
                    }
                    if($r->distance_meters){
                        $lastDistance = $r->distance_meters;
                    }
                }

                if($dayTotal > 0){
                    $dailyHours[$date]['total']=$dayTotal;
                    $dailyHours[$date]['hasAttendance']=true;
                    $dailyHours[$date]['distance']=$lastDistance;
                    $monthlyTotalHours += $dayTotal;
                }
            }

            $day->addDay();
        }

        $openSessions = Attendance::where('user_id',$userId)
            ->whereNull('check_out_at')->get();

        $daysPresent = collect($dailyHours)->where('hasAttendance',true)->count();
        $daysAbsent  = collect($dailyHours)
            ->where('isCurrentMonth',true)
            ->where('hasAttendance',false)->count();

        return view('employee.attendance.dashboard',compact(
            'currentMonth','periodStart','periodEnd',
            'startOfMonth','endOfMonth',
            'dailyHours','monthlyTotalHours',
            'openSessions','daysPresent','daysAbsent'
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