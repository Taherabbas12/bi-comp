<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {

            if (!Schema::hasColumn('attendances', 'lat')) {
                $table->decimal('lat', 10, 7)->nullable();
            }

            if (!Schema::hasColumn('attendances', 'lng')) {
                $table->decimal('lng', 10, 7)->nullable();
            }

            if (!Schema::hasColumn('attendances', 'source')) {
                $table->string('source')->nullable();
            }

            if (!Schema::hasColumn('attendances', 'is_inside_office')) {
                $table->boolean('is_inside_office')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng', 'source', 'is_inside_office']);
        });
    }
};