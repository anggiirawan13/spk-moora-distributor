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
            $table->string('npwp', 15)->after('image_name');
        });

        DB::table('distributors')->update([
            'npwp' => DB::raw('company_name'),
        ]);

        Schema::table('distributors', function (Blueprint $table) {
            $table->dropColumn('company_name');
        });
    }

    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->string('company_name', 100)->after('image_name');
        });

        DB::table('distributors')->update([
            'company_name' => DB::raw('npwp'),
        ]);

        Schema::table('distributors', function (Blueprint $table) {
            $table->dropColumn('npwp');
        });
    }
};
