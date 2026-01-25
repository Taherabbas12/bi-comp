<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('file_name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('mime_type')->default('application/octet-stream');
            $table->enum('attachment_type', [
                'id_card',
                'passport',
                'driver_license',
                'birth_certificate',
                'profile_picture',
                'contract',
                'cv',
                'certificate',
                'insurance',
                'bank_account',
                'other',
            ])->default('other');
            $table->text('description')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            // Foreign key
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            // Indexes
            $table->index(['user_id', 'attachment_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_attachments');
    }
};
