<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Member\MemberController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')
->controller(AuthController::class)
->group(function () {
    Route::post('login', 'login');
});

Route::prefix('member')
->controller(MemberController::class)
->group(function () {
    Route::get('', 'index')->middleware('auth:sanctum');
    Route::get('detail/{id}', 'getDetailMember')->middleware('auth:sanctum');
    Route::put('edit/{id}', 'edit')->middleware('auth:sanctum');
    Route::delete('delete/{id}', 'delete')->middleware('auth:sanctum');
});
