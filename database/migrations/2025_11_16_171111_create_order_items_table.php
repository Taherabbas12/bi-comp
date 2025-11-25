<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary(); // <-- تم التغيير إلى uuid
            $table->uuid('order_id')->constrained()->onDelete('cascade'); // <-- تم التغيير إلى uuid
            $table->uuid('laptop_id')->constrained()->onDelete('cascade'); // <-- تم التغيير إلى uuid
            $table->integer('quantity')->default(1);
            $table->integer('price_at_order'); // سعر المنتج وقت الطلب (以防万一 السعر يتغير لاحقًا)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
