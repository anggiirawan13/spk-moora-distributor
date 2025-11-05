<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTerm extends Model {
    use HasFactory;

    protected $table = 'payment_terms';
    
    protected $fillable = [
        'name',
        'description'
    ];

    public function distributors()
    {
        return $this->hasMany(Distributor::class, 'payment_term_id');
    }
}