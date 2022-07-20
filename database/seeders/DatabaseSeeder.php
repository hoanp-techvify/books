<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $record = 1000000;
        try {
            Author::factory()->times(1000)->create();
        } catch (\Exception $e) {
        }
        try {
            User::factory()->times(1000)->create();
        } catch (\Exception $e) {
        }
        $users = User::all();
        $authors = Author::all();

        echo "\nCreating books:\n";
        for ($i = 0; $i < $record / 1000; $i++) {
            $books = Book::factory()->times(1000)->make()->map(function ($book) use($users) {
                return array_merge($book->toArray(), [
                    'publisher_id' => $users->random()->id
                ]);
            });
            Book::insert($books->toArray());
            
            Book::doesntHave('authors')->chunk(300, function ($books) use($authors) {
                $data= [];
                $books->each(function ($book) use(&$data, $authors) {
                    array_push($data, [
                        'author_id' => $authors->random()->id,
                        'book_id' => $book->id
                    ]);
                    array_push($data, [
                        'author_id' => $authors->random()->id,
                        'book_id' => $book->id
                    ]);
                });
    
                DB::table("author_book")->insert($data);
            });
            echo ".";
            
        }
        
    }
}
