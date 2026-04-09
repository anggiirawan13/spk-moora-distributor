<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_batches', function (Blueprint $table) {
            $table->id();
            $table->string('original_file_name')->nullable();
            $table->unsignedInteger('imported_by')->nullable();
            $table->json('stats')->nullable();
            $table->timestamp('admin_approved_at')->nullable();
            $table->unsignedInteger('admin_approved_by')->nullable();
            $table->timestamp('director_approved_at')->nullable();
            $table->unsignedInteger('director_approved_by')->nullable();
            $table->timestamps();

            $table->foreign('imported_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('admin_approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('director_approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_batches');
    }
};
