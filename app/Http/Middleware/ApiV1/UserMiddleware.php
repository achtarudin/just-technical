<?php

namespace App\Http\Middleware\ApiV1;

use Closure;
use Exception;
use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Models\Type\TypeModel;
use App\Models\Otp\OtpRegistrationModel;

class UserMiddleware
{

    public function handle(Request $request, Closure $next)
    {

        try {
            // if (auth()->user() == null) {
            //     $user =  UserModel::query()
            //         ->userIsVerifed()
            //         ->first();
            //     $token = auth()->login($user);
            //     $request->headers->set('Authorization Bearer ', (string) $token);
            // }

            if (auth()->user()) {

                $isUser =  UserModel::query()->userIsVerifed()
                    ->whereId(auth()->user()->id)
                    ->first();

                if ($isUser) {
                    return $next($request);
                }
                auth()->invalidate(true);
            }

            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (Exception $th) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
