<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laptops', function (Blueprint $table) {
            $table->uuid('id')->primary(); // <-- تم التغيير إلى uuid
            $table->integer('quantity')->default(0);
            $table->string('barcode')->unique(); // مهم لتحديد المنتج
            $table->text('name'); // نستخدم text لدعم النصوص الطويلة
            $table->integer('price_numeric'); // السعر كرقم (500 → 500000)
            $table->string('price_display'); // السعر المعروض (500,000 د.ع)
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('processor')->nullable();
            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->string('screen')->nullable();
            $table->boolean('is_touch')->default(false);
            $table->boolean('is_convertible')->default(false);
            $table->string('gpu')->nullable();
            $table->timestamps();
            $table->softDeletes(); // حذف ناعم
        });
    }

    public function down()
    {
        Schema::dropIfExists('laptops');
    }
};
