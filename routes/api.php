<?php

use App\Http\Controllers\AuthController;
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

Route::get('/auth/register', [AuthController::class,'registerForm']);
Route::post('/auth/register', [AuthController::class,'register']);
Route::post('/auth/login', [AuthController::class,'login']);


Route::group(['middleware' => ['auth:sanctum']], function () {
  
    Route::get('/users', [AuthController::class, 'users']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});


