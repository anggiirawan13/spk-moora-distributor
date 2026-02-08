<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_terms', function (Blueprint $table) {
            $table->string('code', 20)->nullable()->after('id');
        });

        $rows = DB::table('payment_terms')->orderBy('id')->get();
        foreach ($rows as $row) {
            $code = 'TP' . str_pad((string) $row->id, 3, '0', STR_PAD_LEFT);
            DB::table('payment_terms')->where('id', $row->id)->update(['code' => $code]);
        }

        Schema::table('payment_terms', function (Blueprint $table) {
            $table->string('code', 20)->nullable(false)->change();
            $table->unique('code');
        });
    }

    public function down(): void
    {
        Schema::table('payment_terms', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
    }
};
