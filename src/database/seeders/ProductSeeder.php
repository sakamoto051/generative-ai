<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@procost.test')->first();
        $userId = $admin?->id ?? 1;

        $products = [
            [
                'code' => 'P-001',
                'name' => '電子基板A',
                'category' => '電子部品',
                'description' => '高性能CPUボード',
                'unit' => '枚',
                'standard_cost' => 5000.00,
                'selling_price' => 8000.00,
                'lead_time_days' => 7,
                'safety_stock' => 50,
                'is_active' => true,
                'created_by' => $userId,
            ],
            [
                'code' => 'P-002',
                'name' => 'プラスチック筐体B',
                'category' => '筐体',
                'description' => '耐熱性プラスチック製外装',
                'unit' => '個',
                'standard_cost' => 1200.00,
                'selling_price' => 2000.00,
                'lead_time_days' => 3,
                'safety_stock' => 100,
                'is_active' => true,
                'created_by' => $userId,
            ],
            [
                'code' => 'P-003',
                'name' => 'アルミ部品C',
                'category' => '金属部品',
                'description' => 'CNC加工アルミニウム部品',
                'unit' => '個',
                'standard_cost' => 3500.00,
                'selling_price' => 5500.00,
                'lead_time_days' => 5,
                'safety_stock' => 30,
                'is_active' => true,
                'created_by' => $userId,
            ],
            [
                'code' => 'P-004',
                'name' => '組立製品D',
                'category' => '完成品',
                'description' => '電子機器組立品',
                'unit' => '台',
                'standard_cost' => 15000.00,
                'selling_price' => 25000.00,
                'lead_time_days' => 10,
                'safety_stock' => 20,
                'is_active' => true,
                'created_by' => $userId,
            ],
            [
                'code' => 'P-005',
                'name' => 'センサーモジュールE',
                'category' => '電子部品',
                'description' => '温度・湿度センサーモジュール',
                'unit' => '個',
                'standard_cost' => 2500.00,
                'selling_price' => 4000.00,
                'lead_time_days' => 4,
                'safety_stock' => 40,
                'is_active' => true,
                'created_by' => $userId,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['code' => $product['code']],
                $product
            );
        }

        $this->command->info('製品マスタのシーディングが完了しました。');
    }
}
