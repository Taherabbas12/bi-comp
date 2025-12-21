<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('telegram_id')->nullable()->after('phone');
            $table->string('address')->nullable()->after('telegram_id');
            $table->date('birth_date')->nullable()->after('address');
            $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
            $table->string('national_id')->nullable()->after('gender');
            $table->text('notes')->nullable()->after('national_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'telegram_id',
                'address',
                'birth_date',
                'gender',
                'national_id',
                'notes',
            ]);
        });
    }
};
