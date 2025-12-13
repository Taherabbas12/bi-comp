<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceQrCode;

class AttendanceQrSeeder extends Seeder
{
    public function run()
    {
        AttendanceQrCode::create([
            'code' => 'BI-COMPANY-QR-ATTENDANCE-2025',
            'is_active' => true,
        ]);
    }
}