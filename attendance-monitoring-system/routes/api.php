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

Route::middleware('auth:api')->group(function () {
    Route::resource('user', 'UserController');
    Route::resource('student', 'StudentController');
    Route::resource('transaction', 'TransactionController');
    Route::resource('announcement', 'AnnouncementController');
    Route::resource('message', 'MessageController');
});

Route::post('/register', 'UserController@register');
Route::post('/login', 'UserController@login');
Route::post('/logout', 'UserController@logout')->middleware('auth:api');
