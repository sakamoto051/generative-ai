<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductionResultRequest extends FormRequest
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
      'production_plan_item_id' => 'required|exists:production_plan_items,id',
      'result_date' => 'required|date',
      'quantity' => 'required|numeric|min:0.0001',
      'defective_quantity' => 'nullable|numeric|min:0',
      'remarks' => 'nullable|string|max:1000',
    ];
  }
}
