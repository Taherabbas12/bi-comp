<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('role_permission', function (Blueprint $table) {
            $table->id(); // <-- يمكن أن يبقى id عادي، لأنه مفتاح أساسي فقط
            $table->uuid('role_id'); // <-- تم التغيير إلى uuid
            $table->uuid('permission_id'); // <-- تم التغيير إلى uuid
            $table->timestamps();
            $table->unique(['role_id', 'permission_id']); // لا يمكن تكرار نفس الصلاحية لدور واحد

            // تعريف العلاقات الأجنبية
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_permission');
    }
};
