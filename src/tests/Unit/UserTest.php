<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_user_model_has_expected_fillable_attributes(): void
    {
        $user = new User();
        $fillable = $user->getFillable();

        $this->assertContains('employee_number', $fillable);
        $this->assertContains('role_id', $fillable);
        $this->assertContains('factory_id', $fillable);
    }

    public function test_user_has_role_relationship(): void
    {
        $user = new User();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $user->role());
    }

    public function test_user_has_factory_relationship(): void
    {
        $user = new User();
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $user->factory());
    }
}
