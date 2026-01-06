<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBomRequest extends FormRequest
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
            'parent_id' => 'required|integer',
            'parent_type' => 'required|string|in:App\Models\Product',
            'child_id' => 'required|integer',
            'child_type' => 'required|string|in:App\Models\Product,App\Models\Material',
            'quantity' => 'required|numeric|min:0',
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

            $data = $this->all();

            $bomService = app(\App\Services\BomService::class);

            if ($bomService->detectCircularReference(
                (int) $data['parent_id'],
                (int) $data['child_id'],
                $data['child_type']
            )) {
                $validator->errors()->add('child_id', 'Circular reference detected. A product cannot contain itself.');
            }
        });
    }
}
