<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Material;
use App\Models\User;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@procost.test')->first();
        $userId = $admin?->id ?? 1;

        $materials = [
            [
                'code' => 'M-001',
                'name' => '鋼板 SS400',
                'category' => '金属材料',
                'description' => '一般構造用鋼板',
                'unit' => 'kg',
                'unit_price' => 120.00,
                'supplier' => '山田鋼材株式会社',
                'lead_time_days' => 7,
                'current_stock' => 5000,
                'safety_stock' => 1000,
                'lot_management' => 'fifo',
                'is_active' => true,
                'created_by' => $userId,
            ],
            [
                'code' => 'M-002',
                'name' => 'ABSプラスチック',
                'category' => '樹脂材料',
                'description' => '汎用エンジニアリングプラスチック',
                'unit' => 'kg',
                'unit_price' => 450.00,
                'supplier' => '東京化学工業',
                'lead_time_days' => 5,
                'current_stock' => 2000,
                'safety_stock' => 500,
                'lot_management' => 'fifo',
                'is_active' => true,
                'created_by' => $userId,
            ],
            [
                'code' => 'M-003',
                'name' => 'アルミ板 A5052',
                'category' => '金属材料',
                'description' => 'アルミニウム合金板',
                'unit' => 'kg',
                'unit_price' => 380.00,
                'supplier' => '山田鋼材株式会社',
                'lead_time_days' => 10,
                'current_stock' => 1500,
                'safety_stock' => 300,
                'lot_management' => 'fifo',
                'is_active' => true,
                'created_by' => $userId,
            ],
            [
                'code' => 'M-004',
                'name' => 'IC チップ STM32',
                'category' => '電子部品',
                'description' => 'マイコンチップ',
                'unit' => '個',
                'unit_price' => 850.00,
                'supplier' => '電子部品商社ABC',
                'lead_time_days' => 14,
                'current_stock' => 10000,
                'safety_stock' => 2000,
                'lot_management' => 'none',
                'is_active' => true,
                'created_by' => $userId,
            ],
            [
                'code' => 'M-005',
                'name' => 'ボルト M6x20',
                'category' => '締結部品',
                'description' => 'ステンレスボルト',
                'unit' => '個',
                'unit_price' => 15.00,
                'supplier' => '大阪ネジ工業',
                'lead_time_days' => 3,
                'current_stock' => 50000,
                'safety_stock' => 10000,
                'lot_management' => 'none',
                'is_active' => true,
                'created_by' => $userId,
            ],
            [
                'code' => 'M-006',
                'name' => '梱包材ダンボール',
                'category' => '包装材料',
                'description' => 'B段ダンボール箱',
                'unit' => '枚',
                'unit_price' => 80.00,
                'supplier' => '関東パッケージ',
                'lead_time_days' => 2,
                'current_stock' => 3000,
                'safety_stock' => 1000,
                'lot_management' => 'none',
                'is_active' => true,
                'created_by' => $userId,
            ],
        ];

        foreach ($materials as $material) {
            Material::firstOrCreate(
                ['code' => $material['code']],
                $material
            );
        }

        $this->command->info('材料マスタのシーディングが完了しました。');
    }
}
