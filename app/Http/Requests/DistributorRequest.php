<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistributorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'npwp' => 'required|regex:/^\\d{15}$/',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'payment_term_id' => 'required|exists:payment_terms,id',
            'delivery_method_id' => 'required|exists:delivery_methods,id',
            'business_scale_id' => 'required|exists:business_scales,id',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'image_name' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'produk harus dipilih',
            'payment_term_id.required' => 'Termin pembayaran harus dipilih',
            'delivery_method_id.required' => 'Metode pengiriman harus dipilih',
            'business_scale_id.required' => 'Skala bisnis harus dipilih',
            'npwp.regex' => 'NPWP harus 15 digit angka',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('npwp')) {
            $this->merge([
                'npwp' => preg_replace('/\\D+/', '', (string) $this->input('npwp')),
            ]);
        }
    }
}
