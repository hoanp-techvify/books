<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_user()
    {
        $user = User::factory()->create();
        $this->assertInstanceOf(User::class, $user);
        $this->assertNotNull($user);
    }
}
