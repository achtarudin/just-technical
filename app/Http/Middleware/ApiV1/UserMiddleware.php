<?php

namespace App\Http\Middleware\ApiV1;

use Closure;
use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Models\Type\TypeModel;

class UserMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() == null) {
            $user =  UserModel::query()
                ->userIsVerifed()
                ->first();
            $token = auth()->login($user);
            $request->headers->set('Authorization Bearer ', (string) $token);
        }

        if (auth()->user()) {
            if (auth()->user()->user_type->type->name == TypeModel::USER) {
                return $next($request);
            }
            auth()->invalidate(true);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
