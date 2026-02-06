<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->string('dist_code', 20)->nullable()->after('id');
        });

        DB::statement("UPDATE distributors SET dist_code = CONCAT('D', LPAD(id, 3, '0')) WHERE dist_code IS NULL OR dist_code = ''");

        Schema::table('distributors', function (Blueprint $table) {
            $table->unique('dist_code');
        });
    }

    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->dropUnique(['dist_code']);
            $table->dropColumn('dist_code');
        });
    }
};
