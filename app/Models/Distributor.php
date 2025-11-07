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
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'distributor_product');
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