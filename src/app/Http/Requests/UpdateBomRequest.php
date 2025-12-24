<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBomRequest extends FormRequest
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
            'parent_id' => 'sometimes|required|integer',
            'parent_type' => 'sometimes|required|string|in:App\Models\Product',
            'child_id' => 'sometimes|required|integer',
            'child_type' => 'sometimes|required|string|in:App\Models\Product,App\Models\Material',
            'quantity' => 'sometimes|required|numeric|min:0',
            'yield_rate' => 'nullable|numeric|between:0,100',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ];
    }
}
