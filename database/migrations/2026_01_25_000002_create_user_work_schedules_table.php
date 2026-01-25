<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_work_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');

            // يوم من أيام الأسبوع (1 = الاثنين، 7 = الأحد)
            $table->integer('day_of_week')->comment('1=Mon, 2=Tue, ..., 7=Sun');

            // أوقات العمل (nullable إذا كان اليوم عطلة)
            $table->time('check_in')->nullable()->comment('وقت الدخول');
            $table->time('check_out')->nullable()->comment('وقت الخروج');

            // هل اليوم عطلة؟
            $table->boolean('is_day_off')->default(false)->comment('هل هذا اليوم عطلة؟');

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            // فهرس فريد: موظف واحد يملك سجل واحد فقط لكل يوم
            $table->unique(['user_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_work_schedules');
    }
};
