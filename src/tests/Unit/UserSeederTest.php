<?php

namespace Tests\Unit;

use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_are_seeded_correctly(): void
    {
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);

        $this->assertDatabaseCount('users', 4);

        $this->assertDatabaseHas('users', ['employee_number' => 'ADMIN001', 'role_id' => 1]);
        $this->assertDatabaseHas('users', ['employee_number' => 'PLANNER001', 'role_id' => 2]);
        $this->assertDatabaseHas('users', ['employee_number' => 'LEADER001', 'role_id' => 3]);
        $this->assertDatabaseHas('users', ['employee_number' => 'ACCOUNTANT001', 'role_id' => 4]);
    }
}
