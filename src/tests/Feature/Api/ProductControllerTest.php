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
}
