<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_status()
    {
        $response = $this->get('/search/book');

        $response->assertStatus(200);

        $response->assertJsonCount(1);
    }
    public function test_result()
    {
        $response = $this->get('/search/book?q=AAAAAAA');

        $response->assertStatus(200);

        $response->assertJsonCount(1);
        $response->assertJsonStructure([
            'data' => [
                [
                    "title",
                    "summary",
                    "publisher",
                    "authors"
                ]
            ]
        ]);
    }
}
