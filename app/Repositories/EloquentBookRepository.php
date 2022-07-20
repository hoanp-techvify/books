<?php
namespace App\Repositories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
 class EloquentBookRepository implements SearchRepository
 {
     public function search(string $term): Collection
     {
         return Book::query()
             ->where(fn ($query) => (
                 $query->where('title', 'LIKE', "%{$term}%")
                     ->orWhere('summary', 'LIKE', "%{$term}%")
             ))
             ->get();
     }
 } 