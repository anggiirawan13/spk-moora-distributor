<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'=> 'required',
            'price'=>'required',
            'manufacture_year'=>'required',
            'brand_id'=>'required',
            'mileage'=>'required',
            'fuel_type_id'=>'required',
            'engine_capacity'=>'required',
            'car_type_id'=>'required',
            'seat_count'=>'required',
            'transmission_type_id'=>'required',
            'color'=>'required',
            'description'=>'required',
            'is_available'=>'required',
        ];
    }
}
