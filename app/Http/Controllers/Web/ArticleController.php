<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $articles = $this->service->search([])->with(['author'])->paginate(5);
        return view('articles.home.articels-index', compact('articles'));
    }

    public function show($id)
    {
        $article = $this->service->findById($id);

        abort_if($article == null, 404);

        return view('articles.home.articels', compact('article'));

    }

    //
}
