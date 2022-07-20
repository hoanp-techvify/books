<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\SearchRepository;
use Illuminate\Http\Request;
use App\Http\Resources\Book;

class BookController extends Controller
{
    public function index(Request $request, SearchRepository $searchRepository)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $from = $page > 0 ? ($page - 1) : 0;
        $size = $limit;
        return new Book($searchRepository->search(request('q'), $from, $size));
    }
}
