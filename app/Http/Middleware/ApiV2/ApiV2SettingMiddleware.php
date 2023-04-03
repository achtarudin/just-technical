<?php

namespace App\Http\Middleware\ApiV2;

use Closure;
use App\Models\UserModel;
use Illuminate\Http\Request;

class ApiV2SettingMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
