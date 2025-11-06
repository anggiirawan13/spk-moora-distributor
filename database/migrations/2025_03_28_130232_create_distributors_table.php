<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('image_name')->nullable();
            $table->string('company_name');
            $table->text('address');
            $table->string('phone');
            $table->string('email');
            $table->foreignId('product_category_id')->constrained('product_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('payment_term_id')->constrained('payment_terms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('delivery_method_id')->constrained('delivery_methods')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('business_scale_id')->constrained('business_scales')->onUpdate('cascade')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributors');
    }
};