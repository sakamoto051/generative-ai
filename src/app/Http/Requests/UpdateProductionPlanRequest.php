<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductionPlanRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true; // TODO: Implement authorization logic
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'period_start' => 'required|date',
      'period_end' => 'required|date|after:period_start',
      'description' => 'nullable|string',
      'items' => 'required|array|min:1',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.quantity' => 'required|numeric|min:0.01',
      'items.*.planned_start_date' => 'nullable|date',
      'items.*.planned_end_date' => 'nullable|date|after_or_equal:items.*.planned_start_date',
    ];
  }
}
