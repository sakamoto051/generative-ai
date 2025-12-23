<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials(): void
    {
        $role = Role::create(['name' => 'User']);
        $factory = Factory::create(['name' => 'Test Factory']);
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'factory_id' => $factory->id,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_user_cannot_login_with_incorrect_credentials(): void
    {
        $role = Role::create(['name' => 'User']);
        $factory = Factory::create(['name' => 'Test Factory']);
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'factory_id' => $factory->id,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    }

    public function test_user_can_login_with_employee_number(): void
    {
        $role = Role::create(['name' => 'User']);
        $factory = Factory::create(['name' => 'Test Factory']);
        $user = User::factory()->create([
            'employee_number' => 'EMP123',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'factory_id' => $factory->id,
        ]);

        $response = $this->postJson('/api/login', [
            'employee_number' => 'EMP123',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }
}
