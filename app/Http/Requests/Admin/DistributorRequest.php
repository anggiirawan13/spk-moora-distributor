<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DistributorRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'product_category_id' => 'required|exists:product_categories,id',
            'payment_term_id' => 'required|exists:payment_terms,id',
            'delivery_method_id' => 'required|exists:delivery_methods,id',
            'business_scale_id' => 'required|exists:business_scales,id',
            'price_score' => 'required|numeric|min:0|max:100',
            'quality_score' => 'required|numeric|min:0|max:100',
            'delivery_score' => 'required|numeric|min:0|max:100',
            'service_score' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'image_name' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'price_score.required' => 'Skor harga harus diisi',
            'price_score.numeric' => 'Skor harga harus berupa angka',
            'price_score.min' => 'Skor harga minimal 0',
            'price_score.max' => 'Skor harga maksimal 100',
            'quality_score.required' => 'Skor kualitas harus diisi',
            'quality_score.numeric' => 'Skor kualitas harus berupa angka',
            'quality_score.min' => 'Skor kualitas minimal 0',
            'quality_score.max' => 'Skor kualitas maksimal 100',
            'delivery_score.required' => 'Skor pengiriman harus diisi',
            'delivery_score.numeric' => 'Skor pengiriman harus berupa angka',
            'delivery_score.min' => 'Skor pengiriman minimal 0',
            'delivery_score.max' => 'Skor pengiriman maksimal 100',
            'service_score.required' => 'Skor layanan harus diisi',
            'service_score.numeric' => 'Skor layanan harus berupa angka',
            'service_score.min' => 'Skor layanan minimal 0',
            'service_score.max' => 'Skor layanan maksimal 100',
            'product_category_id.required' => 'Kategori produk harus dipilih',
            'payment_term_id.required' => 'Termin pembayaran harus dipilih',
            'delivery_method_id.required' => 'Metode pengiriman harus dipilih',
            'business_scale_id.required' => 'Skala bisnis harus dipilih',
        ];
    }
}