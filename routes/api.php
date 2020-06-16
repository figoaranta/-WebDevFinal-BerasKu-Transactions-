<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function(){
	Route::apiResource('orders','Api\v1\OrderController')->only(['index']);
	Route::apiResource('order','Api\v1\OrderController')->only(['show','destroy','update','store']);
	Route::post('checkout/{id}','Api\v1\CheckoutController@store');
});

