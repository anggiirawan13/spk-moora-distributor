<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model {
    use HasFactory;

    protected $table = 'delivery_methods';
    
    protected $fillable = [
        'name',
        'description'
    ];

    public function distributors()
    {
        return $this->hasMany(Distributor::class, 'delivery_method_id');
    }
}