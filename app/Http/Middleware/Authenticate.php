<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // $containsApiv1 = Str::of($this->route()->getPrefix())->contains('apiv1');
        // $containsApiv2 = Str::of($this->route()->getPrefix())->contains('apiv2');

        // if ($containsApiv2 || $containsApiv1) {
        //     // $request->headers->set('Accept', 'application/json');
        //     // return response()->json(['message' => 'Authenticate', 401]);
        // }

        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
