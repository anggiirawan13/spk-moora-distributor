<?php

namespace App\Models;

use App\Models\Concerns\TracksImportBatchVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Alternative extends Model
{
    use HasFactory, TracksImportBatchVisibility;

    protected $table = 'alternatives';

    protected $fillable = [
        'distributor_id',
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

    public function values()
    {
        $relation = $this->hasMany(AlternativeValue::class, 'alternative_id');
        $user = auth()->user();

        return $user ? $relation->visibleTo($user) : $relation;
    }

    public function distributor()
    {
        $relation = $this->belongsTo(Distributor::class, 'distributor_id');
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

