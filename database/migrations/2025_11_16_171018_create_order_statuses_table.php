<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary(); // <-- تم التغيير إلى uuid
            $table->string('name')->unique(); // 'pending', 'confirmed', 'preparing', 'ready', 'shipped', 'delivered', 'cancelled'
            $table->string('display_name'); // 'قيد الانتظار', 'تم التأكيد', ...
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_statuses');
    }
};
