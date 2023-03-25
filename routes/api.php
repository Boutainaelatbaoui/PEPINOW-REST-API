<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\CategoryController;
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

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/resetPassword',[AuthController::class,'resetPassword']);

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/profile',[AuthController::class,'userProfile']);
    Route::post('/updateProfile',[AuthController::class,'update']);
    Route::post('/refresh',[AuthController::class,'refresh']);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('plants/categories/{id}', [PlantController::class, 'filterByCategory']);
    Route::get('viewPlants', [PlantController::class, "index"]);
    Route::get('viewPlants/{id}', [PlantController::class, "show"]);
});

Route::middleware(['auth', 'checkAdminRole'])->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::get('getPlants', [PlantController::class, "index"]);
    Route::get('showPlants/{id}', [PlantController::class, "show"]);
    Route::put('updatePlants/{id}', [PlantController::class, "update"]);
    Route::delete('deletePlants/{id}', [PlantController::class, "destroy"]);
    Route::get('users',[AuthController::class,'index']);
    Route::put('users/updateRole/{id}',[AuthController::class,'updateRole']);
});

Route::middleware(['auth', 'checkSellerRole'])->group(function () {
    Route::apiResource('plants', PlantController::class);
});

