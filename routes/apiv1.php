<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiV1\AdminMiddleware;
use App\Http\Controllers\ApiV1\AdminLoginController;
use App\Http\Controllers\ApiV1\RegistrationController;
use App\Http\Controllers\ApiV1\AdminVerifiedUserController;

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

Route::get('welcome', function (Request $request) {
    return response()->json(['message' => 'welcome to my api'], 200);
});

/**
 * Route For User
 */

Route::resource('registration', RegistrationController::class);


/**
 * Route For Admin
 */

Route::resource('admin/login', AdminLoginController::class);
Route::middleware([AdminMiddleware::class])->group(function () {
    Route::resource('admin/verified-user', AdminVerifiedUserController::class);
});
