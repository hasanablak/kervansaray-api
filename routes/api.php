<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\BasicTokenMiddleware;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user_socket_update', [UserController::class, 'user_socket_update']);
Route::resource('chat.messages', MessagesController::class)->middleware(BasicTokenMiddleware::class);
Route::resource('chat', ChatController::class)->middleware(BasicTokenMiddleware::class);
