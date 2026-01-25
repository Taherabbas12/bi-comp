<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // حذف جدول work_schedule_settings إذا كان موجوداً
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::dropIfExists('work_schedule_settings');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // لا نعيد إنشاء الجدول القديم
    }
};
