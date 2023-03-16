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
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/profile',[AuthController::class,'profile']);
    Route::post('/refresh',[AuthController::class,'refresh']);
    Route::post('/resetPassword',[UserController::class,'resetPassword']);
    Route::put('/update',[UserController::class,'updateProfile']);
    Route::post('/logout',[UserController::class,'logout']);
    Route::get('/showAlbums',[AlbumController::class,'index']);
    Route::get('/showArtists',[ArtistController::class,'index']);
    Route::apiResource('lyrics', LyricsController::class);


});