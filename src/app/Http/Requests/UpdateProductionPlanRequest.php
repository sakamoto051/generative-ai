<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductionPlanRequest extends FormRequest
{
  public function authorize(): bool
  {
    return $this->user()->can('production-plans.edit');
  }

  public function rules(): array
  {
    return [
      'plan_number' => ['required', 'string', 'max:50', Rule::unique('production_plans', 'plan_number')->ignore($this->production_plan)],
      'plan_name' => ['required', 'string', 'max:255'],
      'start_date' => ['required', 'date'],
      'end_date' => ['required', 'date', 'after_or_equal:start_date'],
      'status' => ['required', 'string', 'in:draft,confirmed,in_progress,completed,cancelled'],
      'notes' => ['nullable', 'string', 'max:1000'],
      'items' => ['nullable', 'array'],
      'items.*.product_id' => ['required', 'exists:products,id'],
      'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
      'items.*.scheduled_date' => ['nullable', 'date'],
      'items.*.priority' => ['nullable', 'integer', 'min:1', 'max:10'],
      'items.*.notes' => ['nullable', 'string', 'max:500'],
    ];
  }

  public function attributes(): array
  {
    return [
      'plan_number' => '計画番号',
      'plan_name' => '計画名',
      'start_date' => '開始日',
      'end_date' => '終了日',
      'status' => 'ステータス',
      'notes' => '備考',
      'items.*.product_id' => '製品',
      'items.*.quantity' => '数量',
      'items.*.scheduled_date' => '予定日',
      'items.*.priority' => '優先度',
      'items.*.notes' => '備考',
    ];
  }
}
