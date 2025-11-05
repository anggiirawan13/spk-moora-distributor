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
        'product_category_id',
        'payment_term_id',
        'delivery_method_id',
        'business_scale_id',
        'price_score',
        'quality_score',
        'delivery_score',
        'service_score',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price_score' => 'decimal:2',
        'quality_score' => 'decimal:2',
        'delivery_score' => 'decimal:2',
        'service_score' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
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