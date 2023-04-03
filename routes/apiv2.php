<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiV2\AuthController;
use App\Http\Controllers\ApiV2\AuthorController;
use App\Http\Middleware\ApiV2\ApiV2SettingMiddleware;
use App\Http\Controllers\ApiV2\AuthorArticleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['guest'])->group(function(){
    Route::post('/author/registration', [AuthController::class, 'registrationAuthor']);

    Route::post('/author/login', [AuthController::class, 'loginAuthor']);

    Route::post('/author/forget-password', [AuthController::class, 'forgetPassword']);
});


Route::middleware([ApiV2SettingMiddleware::class])->group(function(){
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/author/account', [AuthorController::class, 'account']);
        Route::put('/author/account', [AuthorController::class, 'updateAccount']);

        Route::apiResource('author/articles', AuthorArticleController::class);

    });
});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
