<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessScale extends Model {
    use HasFactory;

    protected $table = 'business_scales';
    
    protected $fillable = [
        'name',
        'description'
    ];

    public function distributors()
    {
        return $this->hasMany(Distributor::class, 'business_scale_id');
    }
}