<?php

namespace Tests\Unit;

use App\Models\Author;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_user()
    {
        $author = Author::factory()->create();
        $this->assertInstanceOf(Author::class, $author);
        $this->assertNotNull($author);
    }
}
