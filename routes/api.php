<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('un','Auth\AuthController@un')->name('unauthorized');

Route::group(['middleware' => 'api'], function ($router) {
    Route::group(['namespace' => 'Auth'], function (){
        Route::group(['prefix' => 'auth'], function (){
            Route::post('login', 'AuthController@login');
            Route::post('register', 'AuthController@register');
            Route::post('logout', 'AuthController@logout');
            Route::post('refresh', 'AuthController@refresh');
        });
        Route::post('me', 'AuthController@me');
    });
    Route::group(['namespace' => 'Article'], function () {
        Route::apiResource('articles', 'ArticleController');
        Route::post('articles/{article}/comment', 'ArticlesCommentController@store');
        Route::post('articles/{article}/files', 'ArticlesFilesController@store');
    });
});