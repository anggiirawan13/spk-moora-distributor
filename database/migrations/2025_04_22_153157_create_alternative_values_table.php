<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('alternative_values', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('alternative_id');
            $table->unsignedInteger('sub_criteria_id');
            $table->decimal('value', 15, 2);
            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('alternative_id')->references('id')->on('alternatives')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sub_criteria_id')->references('id')->on('sub_criterias')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('alternative_values');
    }
};
