<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('image_name')->nullable();
            $table->unsignedInteger('price');
            $table->year('manufacture_year');
            $table->foreignId('brand_id')->constrained('car_brands')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('mileage');
            $table->foreignId('fuel_type_id')->constrained('fuel_types')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('engine_capacity');
            $table->foreignId('car_type_id')->constrained('car_types')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedTinyInteger('seat_count');
            $table->foreignId('transmission_type_id')->constrained('transmission_types')->onUpdate('cascade')->onDelete('cascade');
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->smallInteger('is_available')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
