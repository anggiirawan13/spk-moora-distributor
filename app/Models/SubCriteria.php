<?php

namespace App\Models;

use App\Models\Concerns\TracksImportBatchVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SubCriteria extends Model
{
    use HasFactory, TracksImportBatchVisibility;

    protected $table = 'sub_criterias';

    protected $fillable = [
        'criteria_id',
        'code',
        'name',
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
        $relation = $this->belongsTo(Criteria::class);
        $user = auth()->user();

        return $user ? $relation->visibleTo($user) : $relation;
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
