<?php

namespace App\Http\Controllers\ApiV2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ServiceApiV2\ApiV2AuthService;
use App\Http\Requests\ApiV2\AuthorUpdateAccountRequest;

class AuthorController extends Controller
{
    protected $service;

    public function __construct(ApiV2AuthService $service)
    {
        $this->service = $service;
    }

    public function account()
    {
        return response()->json([
            'message'   => 'Author Credential',
            'data'      => auth()->user()->only(['email', 'name'])
        ], 201);
    }


    public function updateAccount(AuthorUpdateAccountRequest $request)
    {
        $valid = $request->validated();

        $this->service->updateAccountAuthor($valid);

        return response()->json([
            'message'   => 'Update Account Author is Successfully',
            'data'      => auth()->user()->only(['email', 'name'])
        ], 201);
    }
}
