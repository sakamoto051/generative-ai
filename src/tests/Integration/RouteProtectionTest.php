<?php

namespace Tests\Integration;

use App\Models\User;
use App\Models\Role;
use App\Models\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteProtectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        
        $this->factory = Factory::create(['name' => 'Main Factory']);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role_id' => 1, // System Administrator
            'factory_id' => $this->factory->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/dashboard');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Welcome to Admin Dashboard']);
    }

    public function test_user_cannot_access_unauthorized_role_routes(): void
    {
        $manager = User::factory()->create([
            'role_id' => 2, // Production Manager
            'factory_id' => $this->factory->id,
        ]);

        // Manager trying to access Admin dashboard
        $response = $this->actingAs($manager, 'sanctum')
            ->getJson('/api/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_manager_can_access_planner_routes(): void
    {
        $manager = User::factory()->create([
            'role_id' => 2, // Production Manager
            'factory_id' => $this->factory->id,
        ]);

        $response = $this->actingAs($manager, 'sanctum')
            ->getJson('/api/planner/plans');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Production Plans List']);
    }
}
