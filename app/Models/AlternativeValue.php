<?php

namespace App\Models;

use App\Models\Concerns\TracksImportBatchVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlternativeValue extends Model
{
    use HasFactory, TracksImportBatchVisibility;

    protected $table = 'alternative_values';

    protected $fillable = [
        'alternative_id',
        'sub_criteria_id',
        'value',
        'import_batch_id',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
                $model->updated_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }

    public function alternative()
    {
        $relation = $this->belongsTo(Alternative::class, 'alternative_id');
        $user = auth()->user();

        return $user ? $relation->visibleTo($user) : $relation;
    }

    public function subCriteria()
    {
        $relation = $this->belongsTo(SubCriteria::class, 'sub_criteria_id');
        $user = auth()->user();

        return $user ? $relation->visibleTo($user) : $relation;
    }
}
