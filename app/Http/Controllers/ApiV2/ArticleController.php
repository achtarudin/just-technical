<?php

namespace App\Http\Controllers\ApiV2;

use App\Exceptions\ApiV2Exception;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;
use App\ServiceApiV2\ApiV2AllArticelsService;

class ArticleController extends Controller
{

    protected $service;

    public function __construct(ApiV2AllArticelsService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $articles = $this->service->search([])->paginate(10);
        return response()->json([
            'message'   => 'List of Articels of Author To Read',
            'data'      => new ArticleCollection($articles)
        ], 200);

    }

    public function show($id)
    {
        $article = $this->service->findById($id);

        throw_if($article == false, new ApiV2Exception('Articel of Author Not Found', 404));

        return response()->json([
            'message'   => 'Articel of author',
            'data'      => new ArticleResource($article)
        ], 200);
    }

}
