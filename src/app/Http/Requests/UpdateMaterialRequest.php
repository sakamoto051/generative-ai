<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialRequest extends FormRequest
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
            'material_code' => 'sometimes|required|string|unique:materials,material_code,' . $this->route('material')->id,
            'name' => 'sometimes|required|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'standard_price' => 'nullable|numeric|min:0',
            'lead_time' => 'nullable|numeric|min:0',
            'minimum_order_quantity' => 'nullable|numeric|min:0',
            'safety_stock' => 'nullable|numeric|min:0',
            'is_lot_managed' => 'nullable|boolean',
            'has_expiry_management' => 'nullable|boolean',
        ];
    }
}
