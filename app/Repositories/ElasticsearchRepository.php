<?php

namespace App\Repositories;

use App\Models\Book;
use App\Repositories\SearchRepository;
use Elastic\Elasticsearch\Client;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;

class ElasticsearchRepository implements SearchRepository
{
    /** @var Elastic\Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function search(string $query = null, $from = 0, $size = 10): Collection
    {
        if (!$query) {
            return new Collection();
        }
        $items = $this->searchOnElasticsearch($query, $from, $size);
        $data = new Collection(Arr::pluck($items['hits']['hits'], '_source'));

        $data = $data->map(function ($data) {

            return (object)$data;

        });

        return new Collection($data);
    }

    private function searchOnElasticsearch(string $query = '', $from, $size): array
    {
        $model = new Book();
        $items = $this->elasticsearch->search([
            "from" => $from,
            "size" => $size,
            'index' => $model->getSearchIndex(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['title', 'summary', 'authors', 'publisher'],
                        'query' => $query
                    ],
                ],
            ],
        ]);

        return $items->asArray();
    }

    private function buildCollection(array $ids): Collection
    {;
        $ids_ordered = implode(',', $ids);

        return Book::whereIn('id', $ids)->with('authors:name', 'publisher:id,name')->orderByRaw("FIELD(id, $ids_ordered)")->get();
    }
}
