<?php

namespace Tests\Integration;

use App\Models\User;
use App\Models\Role;
use App\Models\Factory;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ItemAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected $factory;
    protected $admin;
    protected $viewer;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        
        $this->factory = Factory::create(['name' => 'Main Factory']);
        
        // Admin user (authorized)
        $this->admin = User::factory()->create([
            'role_id' => 1, // System Administrator
            'factory_id' => $this->factory->id,
        ]);

        // Create a 'Viewer' role (not in the allowed list for writes)
        $viewerRole = Role::create(['name' => 'Viewer']);
        $this->viewer = User::factory()->create([
            'role_id' => $viewerRole->id,
            'factory_id' => $this->factory->id,
        ]);
    }

    public function test_admin_can_create_product(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/products', [
            'product_code' => 'AUTH-001',
            'name' => 'Authorized Product',
        ]);

        $response->assertStatus(201);
    }

    public function test_viewer_cannot_create_product(): void
    {
        Sanctum::actingAs($this->viewer);

        $response = $this->postJson('/api/products', [
            'product_code' => 'NOAUTH-001',
            'name' => 'Unauthorized Product',
        ]);

        $response->assertStatus(403);
    }

    public function test_viewer_can_list_products(): void
    {
        Sanctum::actingAs($this->viewer);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
    }
}
