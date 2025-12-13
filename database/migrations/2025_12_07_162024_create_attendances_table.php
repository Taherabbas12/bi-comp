<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // الموظف
            $table->uuid('user_id');

            // تاريخ العمل (بدون وقت) ليسهل التجميع اليومي
            $table->date('work_date');

            // وقت الحضور والانصراف لهذه الجلسة
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();

            // معلومات تحقق الموقع/الجهاز
            $table->string('ip_address', 45)->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->boolean('is_inside_office')->default(false);

            // مصدر تسجيل الحضور (تطبيق موبايل، ويب، كشك...)
            $table->string('source')->nullable(); // 'web', 'mobile', 'kiosk', ...

            $table->timestamps();

            // العلاقات
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            // فهرس لتسريع الاستعلامات اليومية
            $table->index(['user_id', 'work_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};