<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('distributors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->unique();
            $table->text('image_name')->nullable();
            $table->string('company_name', 100);
            $table->text('address');
            $table->string('phone', 15)->nullable();
            $table->string('email', 50);
            $table->unsignedInteger('payment_term_id');
            $table->unsignedInteger('delivery_method_id');
            $table->unsignedInteger('business_scale_id');
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('payment_term_id')->references('id')->on('payment_terms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('delivery_method_id')->references('id')->on('delivery_methods')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('business_scale_id')->references('id')->on('business_scales')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributors');
    }
};