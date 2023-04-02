<?php

namespace App\Http\Controllers\ApiV1;

use Illuminate\Http\Request;
use App\Services\AdminLoginService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiV1\AdminLoginRequest;

class AdminLoginController extends Controller
{
    protected $service;

    public function __construct(AdminLoginService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json([], 404);
    }

    /**
     * Admin try to login
     */
    public function store(AdminLoginRequest $request)
    {
        $valid = $request->validated();

        $admin = $this->service->search(['email' => $valid['email']])->first();

        if ($admin == null) {
            return response()->json(['error' => 'Unauthorized'], 404);
        }

        if (!$token = auth()->attempt($request->only(['email', 'password']))) {

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Response the token
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function show($id)
    {
        return response()->json([], 404);
    }

    public function update(Request $request, $id)
    {
        return response()->json([], 404);
    }

    public function destroy($id)
    {
        return response()->json([], 404);
    }
}
