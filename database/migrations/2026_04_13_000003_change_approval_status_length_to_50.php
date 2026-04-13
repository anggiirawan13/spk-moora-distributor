<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'business_scales',
        'delivery_methods',
        'payment_terms',
        'distributors',
        'products',
        'distributor_product',
        'criterias',
        'sub_criterias',
        'alternatives',
        'alternative_values',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            DB::statement("ALTER TABLE `{$tableName}` MODIFY `admin_approval_status` VARCHAR(50) NULL");
            DB::statement("ALTER TABLE `{$tableName}` MODIFY `director_approval_status` VARCHAR(50) NULL");
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            DB::statement("ALTER TABLE `{$tableName}` MODIFY `admin_approval_status` VARCHAR(255) NULL");
            DB::statement("ALTER TABLE `{$tableName}` MODIFY `director_approval_status` VARCHAR(255) NULL");
        }
    }
};
