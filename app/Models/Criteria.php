<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $table = 'criterias';

    protected $fillable = [
        'code',
        'name',
        'weight',
        'attribute_type',
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
        return $this->hasMany(SubCriteria::class);
    }
}
