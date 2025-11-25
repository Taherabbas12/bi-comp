<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // <-- تم التغيير إلى uuid
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes(); // حذف ناعم
        });
    }

    public function down()
    {
        Schema::dropIfExists('permissions');
    }
};
