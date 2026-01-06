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

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->hasAny(['parent_id', 'child_id', 'child_type'])) {
                return;
            }

            // Get the BOM model being updated
            $bom = $this->route('bom');

            // Determine effective parent and child (use input or fallback to existing)
            $parentId = $this->input('parent_id', $bom->parent_id);
            $childId = $this->input('child_id', $bom->child_id);
            $childType = $this->input('child_type', $bom->child_type);

            $bomService = app(\App\Services\BomService::class);

            if ($bomService->detectCircularReference(
                (int) $parentId,
                (int) $childId,
                $childType
            )) {
                $validator->errors()->add('child_id', 'Circular reference detected. A product cannot contain itself.');
            }
        });
    }
}
