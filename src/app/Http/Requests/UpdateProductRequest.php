<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'product_code' => 'sometimes|required|string|unique:products,product_code,' . $this->route('product')->id,
            'name' => 'sometimes|required|string|max:255',
            'category' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'standard_cost' => 'nullable|numeric|min:0',
            'standard_manufacturing_time' => 'nullable|numeric|min:0',
            'lead_time' => 'nullable|numeric|min:0',
            'safety_stock' => 'nullable|numeric|min:0',
            'reorder_point' => 'nullable|numeric|min:0',
        ];
    }
}
