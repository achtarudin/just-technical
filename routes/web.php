<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ArticleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('articels.list');
});

Route::get('articels', [ArticleController::class, 'index'])->name('articels.list');
Route::get('articels/{id}/read', [ArticleController::class, 'show'])->name('articels.read');
