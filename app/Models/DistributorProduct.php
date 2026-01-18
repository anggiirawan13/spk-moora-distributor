<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributorProduct extends Model
{
    use HasFactory;

    protected $table = 'distributor_product';

    protected $fillable = [
        'distributor_id',
        'product_id',
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
        return $this->belongsTo(Distributor::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}