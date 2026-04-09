<?php

namespace App\Models;

use App\Models\Concerns\TracksImportBatchVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributorProduct extends Model
{
    use HasFactory, TracksImportBatchVisibility;

    protected $table = 'distributor_product';

    protected $fillable = [
        'distributor_id',
        'product_id',
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

    public function distributor()
    {
        $relation = $this->belongsTo(Distributor::class);
        $user = auth()->user();

        return $user ? $relation->visibleTo($user) : $relation;
    }

    public function product()
    {
        $relation = $this->belongsTo(Product::class);
        $user = auth()->user();

        return $user ? $relation->visibleTo($user) : $relation;
    }
}
