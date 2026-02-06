<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Services\NpwpValidationService;

class DistributorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $distributorId = $this->route('distributor')?->id ?? $this->route('distributor');

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('distributors', 'code')->ignore($distributorId),
            ],
            'name' => 'required|string|max:255',
            'npwp' => 'nullable|regex:/^\\d{15}$/',
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
            'code.unique' => 'Kode distributor sudah digunakan',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $npwp = (string) $this->input('npwp');
            if ($npwp === '' || $this->errors()->has('npwp')) {
                return;
            }

            $service = new NpwpValidationService();
            $result = $service->validate($npwp);
            if (!$result['valid']) {
                $message = $result['message'] ?? 'NPWP tidak valid';
                $validator->errors()->add('npwp', $message);
            }
        });
    }

    protected function prepareForValidation()
    {
        if ($this->has('code')) {
            $this->merge([
                'code' => strtoupper(trim((string) $this->input('code'))),
            ]);
        }

        if ($this->has('npwp')) {
            $this->merge([
                'npwp' => preg_replace('/\\D+/', '', (string) $this->input('npwp')),
            ]);
        }
    }
}
