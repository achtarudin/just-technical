<?php

namespace App\Http\Controllers\ApiV1;

use Illuminate\Http\Request;
use App\Models\Type\TypeModel;
use App\Exceptions\ApiV1Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ApiV1\LoginRequest;
use App\Services\UserRegistrationService;
use App\Http\Requests\ApiV1\ValidateOtpRequest;
use App\Http\Requests\ApiV1\RegistrationRequest;

class UserRegistrationController extends Controller
{
    protected $service;

    public function __construct(UserRegistrationService $service)
    {
        $this->service = $service;
    }

    public function registration(RegistrationRequest $request)
    {
        $valid = $request->validated();
        $user = $this->service->save($valid);
        return response()->json([
            'message' => "Registration Success for email : {$user->email}",
            'data' => [
                'otp' => $user->otp_registration->otp
            ]
        ], 200);
    }

    public function validateOtp(ValidateOtpRequest $request)
    {
        $valid = $request->validated();

        $user = $this->service->search()->userValidateOtp($valid['otp'])->first();

        throw_if($user == null, new ApiV1Exception("User with otp code : {$valid['otp']} not found", 404));

        $user = $this->service->update($user, []);

        return response()->json([
            'message' => "Otp Registration Success to validate email : {$user->email}",
            'data' => $user->only(['email', 'name'])
        ], 200);

        return response()->json([], 404);
    }

    public function login(LoginRequest $request)
    {
        $user = $this->service
            ->search($request->only(['email']))
            ->userIsVerifed()
            ->first();

        throw_if($user == null || $user->user_type->type == TypeModel::ADMIN, new ApiV1Exception("User not found", 404));

        $isValidPasswod = Hash::check($request->password, $user->password);

        throw_if($isValidPasswod == false, new ApiV1Exception("User not found", 404));

        $token = auth()->login($user);

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
}
