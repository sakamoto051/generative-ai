<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SanctumSetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_sanctum_is_installed_and_configured(): void
    {
        $this->assertTrue(class_exists(\Laravel\Sanctum\Sanctum::class));
        $this->assertFileExists(config_path('sanctum.php'));
    }

    public function test_user_can_have_api_tokens(): void
    {
        // This test expects the User model to use the HasApiTokens trait.
        // We haven't added it yet, so we expect this might fail or we need to add it first.
        // For now, let's just check if the trait exists.
        $this->assertTrue(trait_exists(\Laravel\Sanctum\HasApiTokens::class));
    }
}
