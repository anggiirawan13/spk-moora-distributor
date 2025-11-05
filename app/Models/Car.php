<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'name',
        'image_name',
        'price',
        'manufacture_year',
        'brand_id',
        'mileage',
        'fuel_type_id',
        'engine_capacity',
        'car_type_id',
        'seat_count',
        'transmission_type_id',
        'color',
        'description',
        'is_available',
    ];

    protected $casts = [
        'manufacture_year' => 'integer',
        'price' => 'integer',
        'mileage' => 'integer',
        'engine_capacity' => 'integer',
        'seat_count' => 'integer',
        'is_available' => 'boolean',
    ];

    public function carBrand()
    {
        return $this->belongsTo(CarBrand::class, 'brand_id');
    }

    public function carType()
    {
        return $this->belongsTo(CarType::class, 'car_type_id');
    }

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class, 'fuel_type_id');
    }

    public function transmissionType()
    {
        return $this->belongsTo(TransmissionType::class, 'transmission_type_id');
    }
}
