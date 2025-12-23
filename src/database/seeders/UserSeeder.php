<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $factory = Factory::first() ?? Factory::create(['name' => 'Main Factory']);

        $users = [
            [
                'employee_number' => 'ADMIN001',
                'name' => 'Admin User',
                'email' => 'admin@procost.com',
                'password' => Hash::make('password'),
                'role_id' => 1, // System Administrator
                'factory_id' => $factory->id,
            ],
            [
                'employee_number' => 'PLANNER001',
                'name' => 'Planner User',
                'email' => 'planner@procost.com',
                'password' => Hash::make('password'),
                'role_id' => 2, // Production Manager
                'factory_id' => $factory->id,
            ],
            [
                'employee_number' => 'LEADER001',
                'name' => 'Leader User',
                'email' => 'leader@procost.com',
                'password' => Hash::make('password'),
                'role_id' => 3, // Manufacturing Leader
                'factory_id' => $factory->id,
            ],
            [
                'employee_number' => 'ACCOUNTANT001',
                'name' => 'Accountant User',
                'email' => 'accountant@procost.com',
                'password' => Hash::make('password'),
                'role_id' => 4, // Cost Accountant
                'factory_id' => $factory->id,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['employee_number' => $userData['employee_number']], $userData);
        }
    }
}
