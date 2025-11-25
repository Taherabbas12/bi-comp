<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable(); // تاريخ البدء
            $table->date('due_date')->nullable(); // تاريخ الانتهاء
            $table->unsignedTinyInteger('progress_percentage')->default(0); // نسبة الإنجاز (0-100)

            $table->uuid('assigned_to_user_id'); // FK to users (employee)
            $table->uuid('supervisor_user_id')->nullable(); // FK to users (supervisor)
            $table->uuid('created_by_user_id'); // FK to users (creator)

            $table->uuid('priority_id'); // FK to priorities
            $table->uuid('status_id'); // FK to task_statuses

            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('assigned_to_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('supervisor_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('priority_id')->references('id')->on('priorities')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('task_statuses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
