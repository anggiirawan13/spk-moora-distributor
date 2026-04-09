<?php

namespace App\Models;

use App\Models\Concerns\TracksImportBatchVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Criteria extends Model
{
    use HasFactory, TracksImportBatchVisibility;

    protected $table = 'criterias';

    protected $fillable = [
        'code',
        'name',
        'weight',
        'attribute_type',
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

    protected $casts = [
        'weight' => 'float',
    ];

    public function alternativeValues()
    {
        return $this->hasMany(AlternativeValue::class, 'criteria_id');
    }

    public function subCriteria()
    {
        $relation = $this->hasMany(SubCriteria::class);
        $user = auth()->user();

        return $user ? $relation->visibleTo($user) : $relation;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
