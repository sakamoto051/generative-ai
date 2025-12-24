<?php

namespace Tests\Feature\Api;

use App\Models\Material;
use App\Models\Role;
use App\Models\User;
use App\Models\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class MaterialControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $factory;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        
        $this->factory = Factory::create(['name' => 'Main Factory']);
        
        $this->admin = User::factory()->create([
            'role_id' => 1, // System Administrator
            'factory_id' => $this->factory->id,
        ]);
    }

    public function test_can_list_materials(): void
    {
        Material::factory()->count(3)->create();

        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/materials');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_show_material(): void
    {
        $material = Material::factory()->create();

        Sanctum::actingAs($this->admin);

        $response = $this->getJson("/api/materials/{$material->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.material_code', $material->material_code);
    }

    public function test_can_create_material(): void
    {
        Sanctum::actingAs($this->admin);

        $data = [
            'material_code' => 'MAT-001',
            'name' => 'New Material',
            'category' => 'Raw Materials',
            'unit' => 'kg',
            'standard_price' => 50.00,
            'lead_time' => 10,
            'minimum_order_quantity' => 100,
            'safety_stock' => 500,
            'is_lot_managed' => true,
            'has_expiry_management' => false,
        ];

        $response = $this->postJson('/api/materials', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('materials', ['material_code' => 'MAT-001']);
    }

    public function test_cannot_create_material_with_duplicate_code(): void
    {
        Material::factory()->create(['material_code' => 'DUP-MAT']);
        
        Sanctum::actingAs($this->admin);

        $data = [
            'material_code' => 'DUP-MAT',
            'name' => 'Another Material',
        ];

        $response = $this->postJson('/api/materials', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['material_code']);
    }

    public function test_can_update_material(): void
    {
        $material = Material::factory()->create(['name' => 'Old Material Name']);

        Sanctum::actingAs($this->admin);

        $response = $this->putJson("/api/materials/{$material->id}", [
            'name' => 'Updated Material Name',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('materials', [
            'id' => $material->id,
            'name' => 'Updated Material Name',
        ]);
    }

    public function test_can_delete_material(): void
    {
        $material = Material::factory()->create();

        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson("/api/materials/{$material->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('materials', ['id' => $material->id]);
    }
}
