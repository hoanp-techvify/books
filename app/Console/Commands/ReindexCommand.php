<?php
namespace App\Console\Commands;

use App\Models\Book;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all articles to Elasticsearch';

    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
    }

    public function handle()
    {
        $model = new Book();

        $params = [
            'index' => $model->getSearchIndex(),
            'body' => [
                'mappings' => [
                    'properties' => [
                        'title' => [
                            'type' => 'text'
                        ],
                        'summary' => [
                            'type' => 'text'
                        ],
                        'publisher' => [
                            'type' => 'text'
                        ],
                        'authors' => [
                            'type' => 'text'
                        ]
                    ]
                ]
            ]
        ];

        try {
            $this->elasticsearch->indices()->create($params);
        } catch (\Exception $e) {
        }

        Book::chunk(1000, function ($books) {
            $params = [];
            $books->each(function (Book $book) use (&$params) {
                $params['body'][] = [
                    'index' => [
                        '_index' => $book->getSearchIndex(),
                        '_id' => $book->id,
                    ]
                ];

                $params['body'][] = [
                    'title' => $book->title,
                    'summary' => $book->summary,
                    'publisher' => $book->publisher?->name,
                    'authors' => $book->authors?->pluck('name')->toArray()
                ];
            });

            $this->elasticsearch->bulk($params);
            $this->output->write('.');
        });

        $this->info("\nDone!");
    }
}
