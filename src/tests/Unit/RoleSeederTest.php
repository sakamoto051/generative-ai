<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_roles_are_seeded_correctly(): void
    {
        $this->seed(RoleSeeder::class);

        $this->assertDatabaseCount('roles', 4);

        $this->assertDatabaseHas('roles', ['id' => 1, 'name' => 'System Administrator']);
        $this->assertDatabaseHas('roles', ['id' => 2, 'name' => 'Production Manager']);
        $this->assertDatabaseHas('roles', ['id' => 3, 'name' => 'Manufacturing Leader']);
        $this->assertDatabaseHas('roles', ['id' => 4, 'name' => 'Cost Accountant']);
    }
}
