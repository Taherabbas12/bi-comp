<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedTinyInteger('score')->nullable()->comment('التقييم من 1 إلى 10');
            $table->unsignedTinyInteger('outcome_rating')->nullable()->comment('تقييم الناتج من 0 إلى 100');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['score', 'outcome_rating']);
        });
    }
};
