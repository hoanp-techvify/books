<?php

namespace App\Models;

use App\Search\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use Searchable;
    use HasFactory;

    public function toElasticsearchDocumentArray(): array
    {
        return [
            'title' => $this->title,
            'summary' => $this->summary,
            'publisher' => $this->publisher?->name,
            'authors' => $this->authors()?->pluck('name')->toArray()
        ];
    }

    public function getSearchIndex()
    {
        return 'books';
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }
}
