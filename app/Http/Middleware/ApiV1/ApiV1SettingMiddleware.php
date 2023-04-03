<?php

namespace App\Http\Middleware\ApiV1;

use Closure;
use Illuminate\Http\Request;

class ApiV1SettingMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        config(['auth.defaults.guard' => 'api']);
        return $next($request);
    }
}
