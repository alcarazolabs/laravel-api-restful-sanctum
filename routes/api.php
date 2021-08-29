<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/userinfo', [AuthController::class, 'userInfo'])->middleware('auth:sanctum');

/*

Headers for test /userinfo endpoint:
Content-Type: application/x-www-form-urlencoded
Accept: application/json
Authorization: Bearer 8|VZh7C66efxx41DAoDCXuBZ0UkFfWSwZeIb0dE3Ou
*/