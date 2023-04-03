<?php

namespace App\Http\Controllers\ApiV2;

use App\Http\Controllers\Controller;
use App\ServiceApiV2\ApiV2AuthService;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\ApiV2\AuthorLoginRequest;
use App\Http\Requests\ApiV2\AuthorRegistrationRequest;
use App\Http\Requests\ApiV2\AuthorForgePasswordtRequest;

class AuthController extends Controller
{
    protected $service;

    public function __construct(ApiV2AuthService $service)
    {
        $this->service = $service;
    }

    public function registrationAuthor(AuthorRegistrationRequest $request)
    {
        $user = $this->service->createAuthor($request->except('password_confirmation'));

        return response()->json([
            'message'   => 'Author Register Successfully',
            'data'      => $user->only(['name', 'email'])
        ], 201);
    }

    public function loginAuthor(AuthorLoginRequest $request)
    {
        $user = $this->service->findAuthor($request->only('email'));

        return response()->json([
            'message'   => 'Author Logged Successfully',
            'data'      => ['token' => $user->createToken("API TOKEN")->plainTextToken]
        ], 201);
    }

    public function forgetPassword(AuthorForgePasswordtRequest $request)
    {
        $user = $this->service->findAuthor($request->only('email'));

        Password::createToken($user);

        return response()->json([
            'message'   => 'Password Reset, Sent To Your Email',
            'data'      => 'But, I am not setting smtp email '
        ], 201);
    }
}
