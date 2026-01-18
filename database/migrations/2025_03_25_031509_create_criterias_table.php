<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('criterias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 5)->unique();
            $table->string('name', 100)->index();
            $table->decimal('weight', 5, 2);
            $table->enum('attribute_type', ['Benefit', 'Cost']);
            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('criterias');
    }
};
