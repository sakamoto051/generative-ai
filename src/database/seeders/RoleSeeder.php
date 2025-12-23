<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'System Administrator', 'description' => 'Full system access'],
            ['id' => 2, 'name' => 'Production Manager', 'description' => 'Planning and BOM management'],
            ['id' => 3, 'name' => 'Manufacturing Leader', 'description' => 'Execution and reporting'],
            ['id' => 4, 'name' => 'Cost Accountant', 'description' => 'Financial analysis and cost calculation'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['id' => $role['id']], $role);
        }
    }
}
