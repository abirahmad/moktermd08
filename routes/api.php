<?php

use App\Http\Controllers\AuthController;
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

Route::resource('posts', PostController::class)->only([
    'destroy', 'show', 'store', 'update'
 ]);

    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('addProfile', 'App\Http\Controllers\ProfileController@addProfile');
    Route::post('editProfile', 'App\Http\Controllers\ProfileController@editProfile');
    Route::post('deleteProfile', 'App\Http\Controllers\ProfileController@deleteProfile');
 