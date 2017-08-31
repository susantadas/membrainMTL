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
Route::get('v1/leadprocessing','Api\LeadProcessingController@index');
Route::post('v1/leadprocessing/getdata','Api\LeadProcessingController@getData');
Route::get('v1/payloadvalidation','Api\PayloadValidationController@index');
Route::get('v1/payloadvalidation/suppliers','Api\PayloadValidationController@suppliersApi');
Route::get('v1/supplierapi','Api\SupplierapiController@index');
Route::any('v1/supplierapi/newapi','Api\SupplierapiController@newApi');
Route::post('v1/supplierapi/','Api\SupplierapiController@store');
Route::post('v1/clientapi/','Api\SupplierapiController@clientapi');
Route::post('v1/{country}/multisupplierapi','Api\SupplierapiController@multisupplierapi');