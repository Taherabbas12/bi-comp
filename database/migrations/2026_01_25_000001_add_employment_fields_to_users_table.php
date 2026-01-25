<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // الراتب والتوظيف
            $table->decimal('salary', 12, 2)->nullable()->after('notes')->comment('الراتب الشهري');
            $table->string('salary_currency')->default('IQD')->after('salary')->comment('عملة الراتب');
            $table->enum('employment_type', ['full-time', 'part-time', 'contract', 'temporary'])->nullable()->after('salary_currency')->comment('نوع التوظيف');
            $table->string('department')->nullable()->after('employment_type')->comment('القسم/الإدارة');
            $table->string('position')->nullable()->after('department')->comment('المسمى الوظيفي');
            $table->date('hire_date')->nullable()->after('position')->comment('تاريخ التعيين');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'salary',
                'salary_currency',
                'employment_type',
                'department',
                'position',
                'hire_date',
            ]);
        });
    }
};
