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
}
