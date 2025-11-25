<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary(); // <-- UUID
            $table->uuid('user_id')->nullable(); // <-- تم تغييره إلى uuid
            $table->uuid('order_status_id')->nullable(); // <-- تم تغييره إلى uuid
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->text('order_notes')->nullable();
            $table->text('notes')->nullable();       // <-- الحقل الجديد
            $table->string('source');
            $table->string('payment_type');
            $table->integer('installment_months')->nullable();
            $table->integer('total_amount');
            $table->uuid('employee_id')->nullable(); // <-- تم تغييره إلى uuid
            $table->timestamps();
            $table->softDeletes();

            // تعريف العلاقات الأجنبية يدويًا بعد تعريف الأعمدة
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_status_id')->references('id')->on('order_statuses')->onDelete('set null');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
