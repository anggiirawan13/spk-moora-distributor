<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    use HasFactory;

    protected $table = 'distributors';

    protected $fillable = [
        'name',
        'image_name',
        'company_name',
        'address',
        'phone',
        'email',
        'payment_term_id',
        'delivery_method_id',
        'business_scale_id',
        'description',
        'is_active',
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
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'distributor_product');
    }

    public function alternative()
    {
        return $this->hasOne(Alternative::class, 'distributor_id');
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class, 'payment_term_id');
    }

    public function deliveryMethod()
    {
        return $this->belongsTo(DeliveryMethod::class, 'delivery_method_id');
    }

    public function businessScale()
    {
        return $this->belongsTo(BusinessScale::class, 'business_scale_id');
    }
}