<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceQrCode;
use Illuminate\Http\Request;

class AttendanceQrController extends Controller
{
    public function show()
    {
        // نفترض أن عندك row واحد في attendance_qr_codes
        $qr = AttendanceQrCode::first();

        return view('admin.attendance.qr', [
            'qr' => $qr,
        ]);
    }

    public function verifyLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        if (! $this->isInsideOffice($request->lat, $request->lng)) {
            return response()->json([
                'allowed' => false,
                'message' => 'لا يمكنك عرض أو توليد رمز الحضور إلا من داخل الشركة.',
            ], 403);
        }

        return response()->json([
            'allowed' => true,
            'message' => 'تم التحقق من موقعك بنجاح.',
        ]);
    }

    private function isInsideOffice(float $lat, float $lng): bool
    {
        // إحداثيات الشركة (التي أعطيتني إياها محوّلة إلى decimal)
        $officeLat = 32.4625278;
        $officeLng = 44.3990550;
        $maxDistance = 120; // متر

        $distance = $this->distanceInMeters($officeLat, $officeLng, $lat, $lng);
        return $distance <= $maxDistance;
    }

    private function distanceInMeters($lat1, $lon1, $lat2, $lon2): float
    {
        $earth = 6371000; // متر

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earth * $c;
    }
}