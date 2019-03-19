<?php

use Illuminate\Http\Request;

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


Route::group(['middleware' => 'api'], function ($router) {
    Route::group(['prefix' => 'auth'], function (){
        Route::post('login', 'Auth\AuthController@login');
        Route::post('register', 'Auth\AuthController@register');
        Route::post('logout', 'Auth\AuthController@logout');
        Route::post('refresh', 'Auth\AuthController@refresh');
        Route::post('me', 'Auth\AuthController@me');
    });
});