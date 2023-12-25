<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('logout', [AuthenticationController::class, 'logout']);
    Route::get('me', [AuthenticationController::class, 'me']);
    Route::post('posts', [PostController::class, 'store']);
    Route::patch('posts/{id}', [PostController::class, 'update']);
    Route::delete('posts/{id}', [PostController::class, 'destroy']);
});

Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{id}', [PostController::class, 'showNewsAndWriter']);
Route::get('postsnew/{id}', [PostController::class, 'showNews']);

Route::post('login', [AuthenticationController::class, 'login']);