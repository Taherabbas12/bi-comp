<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('role_id')->nullable(); // <-- تأكد من أن الحقل هو UUID

            // تعريف العلاقة الأجنبية يدويًا بعد تعريف العمود
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']); // حذف العلاقة الأجنبية أولاً
            $table->dropColumn('role_id');
        });
    }
};
