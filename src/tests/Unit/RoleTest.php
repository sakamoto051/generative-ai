<?php

namespace Tests\Unit;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_role_can_be_created()
    {
        $role = Role::factory()->create([
            'name' => 'Test Role',
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Test Role',
        ]);
    }
}