<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('priorities', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false)->after('color_code'); // أو 'before' إذا أردت
        });
    }

    public function down()
    {
        Schema::table('priorities', function (Blueprint $table) {
            $table->dropColumn('is_primary');
        });
    }
};
