<?php

namespace App\Http\Controllers\ApiV2;

use Illuminate\Http\Request;
use App\Exceptions\ApiV2Exception;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;
use App\ServiceApiV2\ApiV2ArticelService;
use App\Http\Requests\ApiV2\AuthorCreateArticleRequest;
use App\Http\Requests\ApiV2\AuthorUpdateArticleRequest;

class AuthorArticleController extends Controller
{
    protected $service;

    public function __construct(ApiV2ArticelService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = $this->service->search([])->paginate(10);
        return response()->json([
            'message'   => 'List of Articels of Author',
            'data'      => new ArticleCollection($articles)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorCreateArticleRequest $request)
    {
        $valid = $request->validated();

        $article = $this->service->save($valid);

        return response()->json([
            'message'   => 'New Article  of Author',
            'data'      => new ArticleResource($article)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = $this->service->findById($id);

        throw_if($article == false, new ApiV2Exception('Articel of Author Not Found', 404));

        return response()->json([
            'message'   => 'Articel of author',
            'data'      => new ArticleResource($article)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AuthorUpdateArticleRequest $request, $id)
    {
        $article = $this->service->findById($id);

        throw_if($article == false, new ApiV2Exception('Articel of Author Not Found', 404));

        $valid = $request->validated();

        $article = $this->service->update($article, $valid);

        return response()->json([
            'message'   => 'Update Articel of author',
            'data'      => new ArticleResource($article)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = $this->service->findById($id);

        throw_if($article == false, new ApiV2Exception('Articel of Author Not Found', 404));

        $this->service->delete($article);

        return response()->json([
            'message'   => 'Delete Articel of author',
            'data'      => new ArticleResource($article)
        ], 200);


    }
}
