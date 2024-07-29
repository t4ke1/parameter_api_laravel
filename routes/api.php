<?php

use App\Http\Controllers\ParameterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::delete('/deletePictures/{id}', [ParameterController::class, 'deletePictures']);

Route::post('/addPictures', [ParameterController::class, 'addPictures']);
Route::post('/updatePictures', [ParameterController::class, 'updatePictures']);

Route::get('/getUpLoadableParameters', [ParameterController::class, 'getUpLoadableParameters']);
Route::get('/getLoadableParameters', [ParameterController::class, 'getLoadableParameters']);
Route::get('/findByIdOrTitle/{key}', [ParameterController::class, 'findByIdOrTitle']);
Route::get('/getCustomList', [ParameterController::class, 'getCustomList']);
