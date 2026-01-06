<?php

namespace Tests\Feature\Api;

use App\Models\Bom;
use App\Models\Product;
use App\Models\Material;
use App\Models\User;
use App\Models\Factory;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class BomControllerTest extends TestCase
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

    public function test_can_list_boms(): void
    {
        $product = Product::factory()->create();
        $material = Material::factory()->create();
        
        Bom::create([
            'parent_id' => $product->id,
            'parent_type' => Product::class,
            'child_id' => $material->id,
            'child_type' => Material::class,
            'quantity' => 10,
        ]);

        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/boms');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_create_bom_entry(): void
    {
        $product = Product::factory()->create();
        $material = Material::factory()->create();

        Sanctum::actingAs($this->admin);

        $data = [
            'parent_id' => $product->id,
            'parent_type' => Product::class,
            'child_id' => $material->id,
            'child_type' => Material::class,
            'quantity' => 5.5,
            'yield_rate' => 98.5,
            'valid_from' => now()->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/boms', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('boms', [
            'parent_id' => $product->id,
            'child_id' => $material->id,
            'quantity' => 5.5,
        ]);
    }

    public function test_can_update_bom_entry(): void
    {
        $product = Product::factory()->create();
        $material = Material::factory()->create();
        
        $bom = Bom::create([
            'parent_id' => $product->id,
            'parent_type' => Product::class,
            'child_id' => $material->id,
            'child_type' => Material::class,
            'quantity' => 10,
        ]);

        Sanctum::actingAs($this->admin);

        $response = $this->putJson("/api/boms/{$bom->id}", [
            'quantity' => 20,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('boms', [
            'id' => $bom->id,
            'quantity' => 20,
        ]);
    }

    public function test_can_delete_bom_entry(): void
    {
        $product = Product::factory()->create();
        $material = Material::factory()->create();
        
        $bom = Bom::create([
            'parent_id' => $product->id,
            'parent_type' => Product::class,
            'child_id' => $material->id,
            'child_type' => Material::class,
            'quantity' => 10,
        ]);

        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson("/api/boms/{$bom->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('boms', ['id' => $bom->id]);
    }

    public function test_unauthenticated_user_cannot_access_api(): void
    {
        $response = $this->getJson('/api/boms');
        $response->assertStatus(401);
    }

    public function test_prevents_circular_reference_creation(): void
    {
        $productA = Product::factory()->create();
        $productB = Product::factory()->create();

        // A -> B
        Bom::create([
            'parent_id' => $productA->id,
            'parent_type' => Product::class,
            'child_id' => $productB->id,
            'child_type' => Product::class,
            'quantity' => 1,
        ]);

        Sanctum::actingAs($this->admin);

        // Try B -> A
        $response = $this->postJson('/api/boms', [
            'parent_id' => $productB->id,
            'parent_type' => Product::class,
            'child_id' => $productA->id,
            'child_type' => Product::class,
            'quantity' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['child_id']); 
    }
}
