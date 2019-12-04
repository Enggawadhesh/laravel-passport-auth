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

Route::namespace('Api')->group(function () {

	Route::post('/register', 'AuthController@userRegister');
	Route::post('/login', 'AuthController@userLogin');

	Route::middleware('auth:api')->post('/change-password', 'AuthController@changePassword');
	Route::middleware('auth:api')->post('/logout', 'AuthController@userLogout');
	Route::middleware('auth:api')->get('/user', function (Request $request) {
	    return $request->user();
	});
      
});
