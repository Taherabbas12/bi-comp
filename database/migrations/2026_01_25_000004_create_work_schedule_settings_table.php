<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول أوقات العمل الثابتة للشركة
        Schema::create('work_schedule_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // أوقات العمل الرسمية (نفسها لكل الموظفين)
            $table->time('official_check_in')->comment('وقت الدخول الرسمي');
            $table->time('official_check_out')->comment('وقت الخروج الرسمي');

            // ساعات العمل الرسمية يومياً (بالساعات)
            $table->decimal('working_hours', 4, 2)->default(8)->comment('عدد ساعات العمل اليومية');

            // عدد أيام العمل في الأسبوع (افتراضي 6 أيام)
            $table->integer('working_days_per_week')->default(6)->comment('عدد أيام العمل في الأسبوع');

            // اليوم الأساسي للإجازة (1=الاثنين، 7=الأحد)
            $table->integer('default_day_off')->default(5)->comment('اليوم الافتراضي للإجازة الأسبوعية (1=Mon, 7=Sun)');

            $table->timestamps();
        });

        // جدول أيام الإجازة لكل موظف (قد تختلف عن الافتراضي)
        Schema::create('user_day_offs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');

            // يوم من أيام الأسبوع (1 = الاثنين، 7 = الأحد)
            $table->integer('day_of_week')->comment('1=Mon, 2=Tue, ..., 7=Sun');

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            // فهرس فريد لضمان يوم إجازة واحد فقط لكل موظف
            $table->unique(['user_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_day_offs');
        Schema::dropIfExists('work_schedule_settings');
    }
};
