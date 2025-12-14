<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを実行する権限があるか判定
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('products.create');
    }

    /**
     * バリデーションルール
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:products,code'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:20'],
            'standard_cost' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'lead_time_days' => ['required', 'integer', 'min:0'],
            'safety_stock' => ['required', 'integer', 'min:0'],
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
            'code.required' => '製品コードは必須です。',
            'code.unique' => 'この製品コードは既に登録されています。',
            'name.required' => '製品名は必須です。',
            'unit.required' => '単位は必須です。',
            'standard_cost.required' => '標準原価は必須です。',
            'standard_cost.min' => '標準原価は0以上で入力してください。',
            'lead_time_days.required' => 'リードタイムは必須です。',
            'safety_stock.required' => '安全在庫数は必須です。',
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
            'code' => '製品コード',
            'name' => '製品名',
            'category' => 'カテゴリ',
            'unit' => '単位',
            'standard_cost' => '標準原価',
            'selling_price' => '販売価格',
            'lead_time_days' => 'リードタイム',
            'safety_stock' => '安全在庫数',
        ];
    }
}
