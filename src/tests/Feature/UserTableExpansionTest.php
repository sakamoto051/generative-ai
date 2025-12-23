<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserTableExpansionTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumn('users', 'employee_number'), 'The users table is missing the employee_number column.');
        $this->assertTrue(Schema::hasColumn('users', 'role_id'), 'The users table is missing the role_id column.');
        $this->assertTrue(Schema::hasColumn('users', 'factory_id'), 'The users table is missing the factory_id column.');
    }

    public function test_user_can_be_persisted_to_database(): void
    {
        $userData = [
            'employee_number' => 'EMP001',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'factory_id' => 1,
        ];

        \App\Models\User::create($userData);

        $this->assertDatabaseHas('users', [
            'employee_number' => 'EMP001',
            'email' => 'john@example.com',
            'role_id' => 1,
            'factory_id' => 1,
        ]);
    }
}
