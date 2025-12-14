<?php

namespace Tests\Feature;

use App\Models\ProductionPlan;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionPlanFeatureTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    $this->withoutVite();
  }

  public function test_user_can_view_edit_page_for_draft_plan()
  {
    $user = User::factory()->create();
    $this->actingAs($user);

    $plan = ProductionPlan::factory()->create([
      'status' => 'draft',
      'creator_id' => $user->id,
    ]);

    $response = $this->get(route('production-plans.edit', $plan));

    $response->assertStatus(200);
    $response->assertViewIs('production-plans.edit');
    $response->assertSee('Edit Production Plan');
  }

  public function test_user_cannot_view_edit_page_for_approved_plan()
  {
    $user = User::factory()->create();
    $this->actingAs($user);

    $plan = ProductionPlan::factory()->create([
      'status' => 'approved',
      'creator_id' => $user->id,
    ]);

    $response = $this->get(route('production-plans.edit', $plan));

    $response->assertStatus(302); // Redirect back
    $response->assertSessionHas('error');
  }

  public function test_user_can_update_draft_plan()
  {
    $user = User::factory()->create();
    $this->actingAs($user);

    $product = Product::factory()->create(['type' => 'product']);

    $plan = ProductionPlan::factory()->create([
      'status' => 'draft',
      'creator_id' => $user->id,
    ]);

    $updateData = [
      'period_start' => '2024-02-01',
      'period_end' => '2024-02-28',
      'description' => 'Updated Description',
      'items' => [
        [
          'product_id' => $product->id,
          'quantity' => 150,
        ]
      ]
    ];

    $response = $this->put(route('production-plans.update', $plan), $updateData);

    $response->assertRedirect(route('production-plans.show', $plan));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('production_plans', [
      'id' => $plan->id,
      'description' => 'Updated Description',
    ]);

    $this->assertDatabaseHas('production_plan_items', [
      'production_plan_id' => $plan->id,
      'product_id' => $product->id,
      'quantity' => 150,
    ]);
  }
}
