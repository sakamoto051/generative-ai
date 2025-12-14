<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを実行する権限があるか判定
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('materials.create');
    }

    /**
     * バリデーションルール
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:materials,code'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:20'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:200'],
            'lead_time_days' => ['required', 'integer', 'min:0'],
            'current_stock' => ['required', 'integer', 'min:0'],
            'safety_stock' => ['required', 'integer', 'min:0'],
            'lot_management' => ['required', 'in:none,fifo,lifo'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * バリデーションエラーメッセージ
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => '材料コードは必須です。',
            'code.unique' => 'この材料コードは既に登録されています。',
            'name.required' => '材料名は必須です。',
            'unit.required' => '単位は必須です。',
            'unit_price.required' => '単価は必須です。',
            'unit_price.min' => '単価は0以上で入力してください。',
            'lead_time_days.required' => 'リードタイムは必須です。',
            'current_stock.required' => '現在庫数は必須です。',
            'safety_stock.required' => '安全在庫数は必須です。',
            'lot_management.required' => 'ロット管理は必須です。',
        ];
    }

    /**
     * バリデーション用の属性名
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'code' => '材料コード',
            'name' => '材料名',
            'category' => 'カテゴリ',
            'unit' => '単位',
            'unit_price' => '単価',
            'supplier' => '仕入先',
            'lead_time_days' => 'リードタイム',
            'current_stock' => '現在庫数',
            'safety_stock' => '安全在庫数',
            'lot_management' => 'ロット管理',
        ];
    }
}
