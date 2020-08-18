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


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('refresh', 'AuthController@refresh');

    Route::middleware("apiJwt")->group(function () {
        Route::post('logout', 'AuthController@logout');
        Route::post('me', 'AuthController@me');
    });
});

Route::middleware("apiJwt")->group(function () {
    Route::resource('parameters', 'ParameterController');
    Route::resource('customers', 'CustomerController');
    Route::get("/vehicles/getUnassignedVehicles", 'CustomerVehicleController@getUnassignedVehicles');
    Route::resource('vehicles', 'CustomerVehicleController');
    Route::resource('services', 'ServiceController');
    Route::post("/orders/{id}/service", 'OrderController@addService');
    Route::delete("/orders/{id}/service/{serviceId}", 'OrderController@destroyService');
    Route::put("/orders/{id}/service/{serviceId}", 'OrderController@updateService');
    Route::resource('orders', 'OrderController');
    Route::resource('mechanics', 'MechanicController');
});


