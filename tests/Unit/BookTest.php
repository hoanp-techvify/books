<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\User;
use App\Models\Author;
use Tests\TestCase;

class BookTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_user()
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'publisher_id' => $user->id,
            'title' => 'AAAAAAA'
        ]);
        $book->authors()->save($author);
        $this->assertInstanceOf(Book::class, $book);
        $this->assertNotNull($book);
    }
}
