<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductionPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only allow update if status is 'Draft'
        $plan = $this->route('production_plan');
        return $plan && $plan->status === 'Draft';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $plan = $this->route('production_plan');

        return [
            'plan_code' => [
                'sometimes',
                'required',
                'string',
                Rule::unique('production_plans', 'plan_code')->ignore($plan->id),
            ],
            'name' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'details' => 'sometimes|required|array|min:1',
            'details.*.id' => 'nullable|exists:production_plan_details,id',
            'details.*.product_id' => 'required_with:details|exists:products,id',
            'details.*.quantity' => 'required_with:details|numeric|min:0.01',
            'details.*.due_date' => [
                'required_with:details',
                'date',
                // Note: Complex validation across fields (start_date/end_date) 
                // might need a custom rule or closure if start_date/end_date are also being updated.
                // For now, keeping it simple.
            ],
            'details.*.priority' => 'nullable|integer|min:0',
            'details.*.remarks' => 'nullable|string',
        ];
    }
}