<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return $this->user()->can('equipment.create');
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'code' => ['required', 'string', 'max:50', 'unique:equipment,code'],
      'name' => ['required', 'string', 'max:255'],
      'category' => ['nullable', 'string', 'max:100'],
      'process' => ['nullable', 'string', 'max:100'],
      'capacity_per_hour' => ['nullable', 'integer', 'min:0'],
      'setup_time_minutes' => ['nullable', 'integer', 'min:0'],
      'hourly_rate' => ['required', 'numeric', 'min:0'],
      'maintenance_interval_days' => ['nullable', 'integer', 'min:0'],
      'location' => ['nullable', 'string', 'max:200'],
      'notes' => ['nullable', 'string', 'max:1000'],
      'is_active' => ['boolean'],
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
      'code' => '設備コード',
      'name' => '設備名',
      'category' => '設備カテゴリ',
      'process' => '工程',
      'capacity_per_hour' => '生産能力',
      'setup_time_minutes' => '段取り時間',
      'hourly_rate' => '時間チャージ',
      'maintenance_interval_days' => 'メンテナンス周期',
      'location' => '設置場所',
      'notes' => '備考',
      'is_active' => '有効',
    ];
  }
}
