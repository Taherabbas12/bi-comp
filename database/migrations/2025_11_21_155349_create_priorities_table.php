<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('priorities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique(); // e.g., 'urgent', 'important', 'normal'
            $table->string('display_name'); // e.g., 'عاجل', 'مهم', 'عادي'
            $table->string('color_code')->default('#6c757d'); // e.g., #dc3545 for urgent
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('priorities');
    }
};
