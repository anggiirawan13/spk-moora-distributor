<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCriteria extends Model
{
    use HasFactory;

    protected $table = 'sub_criterias';

    protected $fillable = [
        'criteria_id',
        'code',
        'name',
        'value',
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

            if (empty($model->code)) {
                $model->code = self::generateCode($model->criteria_id);
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    private static function generateCode(int $criteriaId): string
    {
        $criteria = Criteria::select('code')->find($criteriaId);
        $prefix = $criteria ? strtoupper($criteria->code) : 'SC';

        $attempts = 0;
        do {
            $count = self::where('criteria_id', $criteriaId)->count() + 1 + $attempts;
            $code = $prefix . '-' . str_pad((string) $count, 3, '0', STR_PAD_LEFT);
            $attempts++;
        } while (self::where('code', $code)->exists() && $attempts < 10);

        return $code;
    }
}
