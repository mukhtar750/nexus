<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'business_name' => 'required|string|max:255',
            'business_address' => 'nullable|string',
            'year_established' => 'nullable|string',
            'business_structure' => 'nullable|string',
            'cac_number' => 'nullable|string',
            'cac_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'nepc_status' => 'nullable|string',
            'nepc_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'recent_export_activity' => 'nullable|string',
            'commercial_scale' => 'nullable|boolean',
            'packaged_for_retail' => 'nullable|boolean',
            'regulatory_registration' => 'nullable|boolean',
            'engaged_logistics' => 'nullable|boolean',
            'received_inquiries' => 'nullable|boolean',
            'production_location' => 'nullable|string',
            'production_compliant' => 'nullable|boolean',
            'production_capacity' => 'nullable|string',
            'active_channels' => 'nullable|string',
            'sales_model' => 'nullable|string',
            'export_objective' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'haccp_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'fda_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'halal_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'son_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }
}
