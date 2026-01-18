<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('email', 50)->unique();
            $table->text('image_name')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->text('password');
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
