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

    public function test_user_can_be_created_with_new_attributes(): void
    {
        $userData = [
            'employee_number' => 'EMP001',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'role_id' => 1,
            'factory_id' => 1,
        ];

        $user = new User($userData);

        $this->assertEquals('EMP001', $user->employee_number);
        $this->assertEquals(1, $user->role_id);
        $this->assertEquals(1, $user->factory_id);
    }
}
