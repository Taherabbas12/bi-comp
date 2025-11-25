<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('task_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique(); // e.g., 'todo', 'in_progress', 'completed'
            $table->string('display_name'); // e.g., 'مطلوب', 'قيد التنفيذ', 'منتهي'
            $table->string('color_code')->default('#6c757d');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_statuses');
    }
};
