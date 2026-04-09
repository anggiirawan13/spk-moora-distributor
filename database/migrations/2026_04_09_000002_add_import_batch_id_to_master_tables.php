<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('import_batch_id')->nullable()->index();
                $table->string('admin_approval_status')->nullable()->index();
                $table->text('admin_approval_note')->nullable();
                $table->timestamp('admin_approved_at')->nullable();
                $table->unsignedInteger('admin_approved_by')->nullable();
                $table->string('director_approval_status')->nullable()->index();
                $table->text('director_approval_note')->nullable();
                $table->timestamp('director_approved_at')->nullable();
                $table->unsignedInteger('director_approved_by')->nullable();
                $table->foreign('import_batch_id')->references('id')->on('import_batches')->nullOnDelete();
                $table->foreign('admin_approved_by')->references('id')->on('users')->nullOnDelete();
                $table->foreign('director_approved_by')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['director_approved_by']);
                $table->dropForeign(['admin_approved_by']);
                $table->dropForeign(['import_batch_id']);
                $table->dropColumn([
                    'import_batch_id',
                    'admin_approval_status',
                    'admin_approval_note',
                    'admin_approved_at',
                    'admin_approved_by',
                    'director_approval_status',
                    'director_approval_note',
                    'director_approved_at',
                    'director_approved_by',
                ]);
            });
        }
    }
};
