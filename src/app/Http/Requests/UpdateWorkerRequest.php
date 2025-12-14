<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkerRequest extends FormRequest
{
  public function authorize(): bool
  {
    return $this->user()->can('workers.edit');
  }

  public function rules(): array
  {
    return [
      'code' => ['required', 'string', 'max:50', Rule::unique('workers', 'code')->ignore($this->worker)],
      'name' => ['required', 'string', 'max:255'],
      'email' => ['nullable', 'email', 'max:255'],
      'phone' => ['nullable', 'string', 'max:20'],
      'skill_level' => ['required', 'string', 'in:初級,中級,上級,熟練'],
      'hourly_wage' => ['required', 'numeric', 'min:0'],
      'hire_date' => ['nullable', 'date'],
      'department' => ['nullable', 'string', 'max:100'],
      'notes' => ['nullable', 'string', 'max:1000'],
      'is_active' => ['boolean'],
    ];
  }

  public function attributes(): array
  {
    return [
      'code' => '作業者コード',
      'name' => '氏名',
      'email' => 'メールアドレス',
      'phone' => '電話番号',
      'skill_level' => 'スキルレベル',
      'hourly_wage' => '時給',
      'hire_date' => '入社日',
      'department' => '所属部署',
      'notes' => '備考',
      'is_active' => '有効',
    ];
  }
}
