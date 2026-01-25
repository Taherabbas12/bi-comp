<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();

            // يوم من أيام الأسبوع (1 = الاثنين، 7 = الأحد)
            $table->integer('day_of_week')->comment('1=Mon, 2=Tue, ..., 7=Sun');

            // أوقات العمل الرسمية
            $table->time('official_check_in')->comment('وقت الدخول الرسمي');
            $table->time('official_check_out')->comment('وقت الخروج الرسمي');

            // ساعات العمل الرسمية يومياً (بالساعات)
            $table->decimal('working_hours', 4, 2)->default(8)->comment('عدد ساعات العمل اليومية');

            // هل اليوم عطلة؟
            $table->boolean('is_day_off')->default(false)->comment('هل هذا اليوم عطلة؟');

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            // فهرس للاستعلامات السريعة
            $table->unique(['user_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_schedules');
    }
};
