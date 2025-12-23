<?php

namespace Tests\Integration;

use App\Models\User;
use App\Models\Role;
use App\Models\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and users
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'UserSeeder']);
    }

    public function test_admin_can_login_and_access_admin_protected_route(): void
    {
        // 1. Login
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'admin@procost.com',
            'password' => 'password',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');

        // 2. Access Protected Route
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/admin/dashboard');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Welcome to Admin Dashboard']);
    }

    public function test_planner_can_login_and_access_planner_protected_route(): void
    {
        // 1. Login
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'planner@procost.com',
            'password' => 'password',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');

        // 2. Access Protected Route
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/planner/plans');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Production Plans List']);
    }

    public function test_leader_cannot_access_admin_protected_route(): void
    {
        // 1. Login
        $loginResponse = $this->postJson('/api/login', [
            'email' => 'leader@procost.com',
            'password' => 'password',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');

        // 2. Try Accessing Admin Route
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/admin/dashboard');

        $response->assertStatus(403);
    }
}
