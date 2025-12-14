<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBomRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return $this->user()->can('boms.edit');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'product_id' => ['required', 'exists:products,id'],
      'material_id' => ['required', 'exists:materials,id'],
      'quantity' => ['required', 'numeric', 'min:0.01'],
      'unit' => ['required', 'string', 'max:10'],
      'sequence' => ['nullable', 'integer', 'min:1'],
      'yield_rate' => ['required', 'numeric', 'min:0', 'max:100'],
      'notes' => ['nullable', 'string', 'max:1000'],
    ];
  }

  /**
   * Get custom attributes for validator errors.
   *
   * @return array<string, string>
   */
  public function attributes(): array
  {
    return [
      'product_id' => '製品',
      'material_id' => '材料',
      'quantity' => '使用数量',
      'unit' => '単位',
      'sequence' => '順序',
      'yield_rate' => '歩留まり率',
      'notes' => '備考',
    ];
  }
}
