<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 権限の作成
        $permissions = [
            // 製品マスタ
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',

            // 材料マスタ
            'materials.view',
            'materials.create',
            'materials.edit',
            'materials.delete',

            // BOMマスタ
            'boms.view',
            'boms.create',
            'boms.edit',
            'boms.delete',

            // 設備マスタ
            'equipment.view',
            'equipment.create',
            'equipment.edit',
            'equipment.delete',

            // 作業者マスタ
            'workers.view',
            'workers.create',
            'workers.edit',
            'workers.delete',

            // 生産計画
            'production-plans.view',
            'production-plans.create',
            'production-plans.edit',
            'production-plans.delete',
            'production-plans.approve',

            // 製造指示
            'manufacturing-orders.view',
            'manufacturing-orders.create',
            'manufacturing-orders.edit',
            'manufacturing-orders.delete',

            // 作業実績
            'work-results.view',
            'work-results.create',
            'work-results.edit',

            // 原価計算
            'costs.view',
            'costs.calculate',
            'costs.approve',

            // レポート
            'reports.view',
            'reports.export',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ロールの作成と権限の割り当て

        // システム管理者 - すべての権限
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // 経営層 - 閲覧と承認
        $executiveRole = Role::firstOrCreate(['name' => 'executive', 'guard_name' => 'web']);
        $executiveRole->syncPermissions([
            'products.view',
            'materials.view',
            'production-plans.view',
            'production-plans.approve',
            'manufacturing-orders.view',
            'costs.view',
            'costs.approve',
            'reports.view',
            'reports.export',
        ]);

        // 生産管理者 - 生産計画、製造指示、閲覧
        $productionManagerRole = Role::firstOrCreate(['name' => 'production_manager', 'guard_name' => 'web']);
        $productionManagerRole->syncPermissions([
            'products.view',
            'materials.view',
            'boms.view',
            'equipment.view',
            'workers.view',
            'production-plans.view',
            'production-plans.create',
            'production-plans.edit',
            'production-plans.delete',
            'manufacturing-orders.view',
            'manufacturing-orders.create',
            'manufacturing-orders.edit',
            'work-results.view',
            'costs.view',
            'reports.view',
            'reports.export',
        ]);

        // 製造現場リーダー - 実績入力、進捗確認
        $shopFloorLeaderRole = Role::firstOrCreate(['name' => 'shop_floor_leader', 'guard_name' => 'web']);
        $shopFloorLeaderRole->syncPermissions([
            'products.view',
            'materials.view',
            'manufacturing-orders.view',
            'work-results.view',
            'work-results.create',
            'work-results.edit',
        ]);

        // 原価計算担当者 - 原価計算、分析、レポート
        $costAccountantRole = Role::firstOrCreate(['name' => 'cost_accountant', 'guard_name' => 'web']);
        $costAccountantRole->syncPermissions([
            'products.view',
            'materials.view',
            'manufacturing-orders.view',
            'work-results.view',
            'costs.view',
            'costs.calculate',
            'reports.view',
            'reports.export',
        ]);

        // 購買担当者 - 材料管理
        $purchasingRole = Role::firstOrCreate(['name' => 'purchasing', 'guard_name' => 'web']);
        $purchasingRole->syncPermissions([
            'materials.view',
            'materials.create',
            'materials.edit',
            'production-plans.view',
            'reports.view',
        ]);

        // 品質管理者 - 検査入力、不良分析
        $qualityRole = Role::firstOrCreate(['name' => 'quality', 'guard_name' => 'web']);
        $qualityRole->syncPermissions([
            'products.view',
            'manufacturing-orders.view',
            'work-results.view',
            'work-results.edit',
            'reports.view',
        ]);

        // 一般作業者 - 実績入力のみ
        $workerRole = Role::firstOrCreate(['name' => 'worker', 'guard_name' => 'web']);
        $workerRole->syncPermissions([
            'work-results.create',
            'work-results.view',
        ]);

        // デフォルトユーザーの作成
        $admin = User::firstOrCreate(
            ['email' => 'admin@procost.test'],
            [
                'name' => '管理者',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('admin');

        $manager = User::firstOrCreate(
            ['email' => 'manager@procost.test'],
            [
                'name' => '生産管理者',
                'password' => bcrypt('password'),
            ]
        );
        $manager->assignRole('production_manager');

        $this->command->info('ロールと権限のシーディングが完了しました。');
    }
}
