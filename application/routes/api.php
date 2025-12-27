<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\CRM\ClientController;
use App\Http\Controllers\API\CRM\ProjectController;
use App\Http\Controllers\API\CRM\TaskController;

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

Route::middleware('auth:api')->post('/client', [ClientController::class, 'store']);

Route::middleware('auth:api')->get('/client/{id}', [ClientController::class, 'show']);

Route::middleware('auth:api')->post('/project', [ProjectController::class, 'store']);

Route::middleware('auth:api')->get('/project/{id}', [ProjectController::class, 'show']);

Route::middleware('auth:api')->post('/task', [TaskController::class, 'store']);

Route::middleware('auth:api')->get('/task/{id}', [TaskController::class, 'show']);

Route::get('/hello', function () {
    return response()->json(['message' => 'Hello from your Laravel API!']);
});

//Route::post('/client', [ClientController::class, 'store']);

//Route::post('/client', [ClientController::class, 'store']);
