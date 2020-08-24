<?php
namespace App\Http\Controllers\API;
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
Route::post('register', 'ApiController@userReg');
Route::post('login', 'ApiController@userLogin');

Route::middleware('auth:api')->group( function () {
    Route::post('add-product', 'ApiController@store');
    Route::post('update-product', 'ApiController@updateProduct');
    Route::post('delete-product', 'ApiController@deleteProduct');
    Route::post('forget-password', 'ApiController@forgotPassword');
});
