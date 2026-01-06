<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Models\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ProductControllerTest extends TestCase
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

    public function test_can_list_products(): void
    {
        Product::factory()->count(3)->create();

        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_show_product(): void
    {
        $product = Product::factory()->create();

        Sanctum::actingAs($this->admin);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.product_code', $product->product_code);
    }

    public function test_can_create_product(): void
    {
        Sanctum::actingAs($this->admin);

        $data = [
            'product_code' => 'PROD-001',
            'name' => 'New Product',
            'category' => 'Electronics',
            'unit' => 'pcs',
            'standard_cost' => 100.00,
            'standard_manufacturing_time' => 10.5,
            'lead_time' => 5,
            'safety_stock' => 50,
            'reorder_point' => 20,
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', ['product_code' => 'PROD-001']);
    }

    public function test_cannot_create_product_with_duplicate_code(): void
    {
        Product::factory()->create(['product_code' => 'DUP-001']);
        
        Sanctum::actingAs($this->admin);

        $data = [
            'product_code' => 'DUP-001',
            'name' => 'Another Product',
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_code']);
    }

    public function test_can_update_product(): void
    {
        $product = Product::factory()->create(['name' => 'Old Name']);

        Sanctum::actingAs($this->admin);

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::factory()->create();

        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_unauthenticated_user_cannot_access_api(): void
    {
        $response = $this->getJson('/api/products');
        $response->assertStatus(401);
    }

    public function test_can_retrieve_bom_tree(): void
    {
        // Need to import Bom and Material
        // Or assume they are imported. But they are not in the original file I read.
        // I will add full namespaces or use imports.
        // Let's rely on imports, I'll add them to the file content in the replace block?
        // No, replace replaces specific string.
        // I should use full class names in test body or check imports.
        
        $productA = Product::factory()->create(['product_code' => 'A']);
        $productB = Product::factory()->create(['product_code' => 'B']);
        $materialC = \App\Models\Material::factory()->create(['material_code' => 'C']);

        \App\Models\Bom::create([
            'parent_id' => $productA->id,
            'parent_type' => Product::class,
            'child_id' => $productB->id,
            'child_type' => Product::class,
            'quantity' => 2,
        ]);

        \App\Models\Bom::create([
            'parent_id' => $productB->id,
            'parent_type' => Product::class,
            'child_id' => $materialC->id,
            'child_type' => \App\Models\Material::class,
            'quantity' => 3,
        ]);

        Sanctum::actingAs($this->admin);

        $response = $this->getJson("/api/products/{$productA->id}/bom-tree");

        $response->assertStatus(200)
            ->assertJsonPath('data.code', 'A')
            ->assertJsonPath('data.children.0.code', 'B')
            ->assertJsonPath('data.children.0.children.0.code', 'C')
            ->assertJsonPath('data.children.0.children.0.total_quantity', 6);
    }
}
