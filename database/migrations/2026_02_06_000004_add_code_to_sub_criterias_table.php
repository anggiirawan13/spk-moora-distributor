<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_criterias', function (Blueprint $table) {
            $table->string('code', 20)->nullable()->after('criteria_id');
        });

        $criteriaCodes = DB::table('criterias')->pluck('code', 'id');
        $counters = [];
        $rows = DB::table('sub_criterias')->orderBy('criteria_id')->orderBy('id')->get();

        foreach ($rows as $row) {
            $criteriaId = (int) $row->criteria_id;
            $prefix = strtoupper($criteriaCodes[$criteriaId] ?? 'SC');
            $counters[$criteriaId] = ($counters[$criteriaId] ?? 0) + 1;
            $code = $prefix . '-' . str_pad((string) $counters[$criteriaId], 3, '0', STR_PAD_LEFT);
            DB::table('sub_criterias')->where('id', $row->id)->update(['code' => $code]);
        }

        Schema::table('sub_criterias', function (Blueprint $table) {
            $table->string('code', 20)->nullable(false)->change();
            $table->unique('code');
        });
    }

    public function down(): void
    {
        Schema::table('sub_criterias', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
    }
};
