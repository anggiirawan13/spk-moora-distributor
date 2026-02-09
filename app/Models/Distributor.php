<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Distributor extends Model
{
    use HasFactory;

    protected $table = 'distributors';

    protected $fillable = [
        'code',
        'name',
        'image_name',
        'npwp',
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

    public function getNpwpFormattedAttribute(): string
    {
        $digits = preg_replace('/\D+/', '', (string) $this->npwp);

        if (strlen($digits) !== 15) {
            return (string) $this->npwp;
        }

        return substr($digits, 0, 2) . '.'
            . substr($digits, 2, 3) . '.'
            . substr($digits, 5, 3) . '.'
            . substr($digits, 8, 1) . '-'
            . substr($digits, 9, 3) . '.'
            . substr($digits, 12, 3);
    }

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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
