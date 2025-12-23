<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        $factory = \App\Models\Factory::first();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'employee_number' => 'TEST001',
            'role_id' => 1, // System Administrator
            'factory_id' => $factory->id,
        ]);
    }
}
