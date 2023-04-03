<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exceptions\ApiV2Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (ApiV2Exception $e, Request $request) {
            $containsApiv2 = Str::of(url()->current())->contains('apiv2');
            if ($request->wantsJson() || $containsApiv2) {
                return response()->json([
                    'message'   => $e->getMessage(),
                    'data'      => $e->getDataOption()
                ], $e->getCode());
            }
        });

        $this->renderable(function (QueryException $e, Request $request) {
            $containsApiv2 = Str::of(url()->current())->contains('apiv2');
            if ($request->wantsJson() || $containsApiv2) {
                return response()->json([
                    'message'   => 'Handler QueryException',
                    'data'      => []
                ], 500);
            }
        });

        $this->reportable(function (Throwable $e) {
        });
    }
}
