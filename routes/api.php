<?php

use App\Http\Controllers\AuthenticationController;
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

Route::prefix('public/auth')->group(function() {
    Route::post('register', [AuthenticationController::class, 'registerUser']);
    Route::post('login', [AuthenticationController::class, 'loginUser']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('protected/users')->middleware('IsCustomer')->group(function () {

    });
});
