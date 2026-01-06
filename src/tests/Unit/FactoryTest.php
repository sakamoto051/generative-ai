<?php

namespace Tests\Unit;

use App\Models\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_factory_can_be_created()
    {
        $factory = Factory::factory()->create([
            'name' => 'Test Factory',
        ]);

        $this->assertDatabaseHas('factories', [
            'name' => 'Test Factory',
        ]);
    }
}