<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

    Route::post('logout', 'AuthController@logout');
    Route::post('me', 'AuthController@me');
});

Route::get("/session", function () {
//    Session::put('progress', '5%');
//    Session::save();
});

Route::middleware(["jwt"])->group(function () {
    Route::resource('parameters', 'ParameterController');
    Route::get("/customers/count", 'CustomerController@countOrders');
    Route::resource('customers', 'CustomerController');
    Route::get("/vehicles/count", 'CustomerVehicleController@countOrders');
    Route::get("/vehicles/getUnassignedVehicles", 'CustomerVehicleController@getUnassignedVehicles');
    Route::resource('vehicles', 'CustomerVehicleController');
    Route::resource('services', 'ServiceController');
    Route::get("/orders/{id}/copy", 'OrderController@copy');
    Route::post("/orders/{id}/position", 'OrderController@addPosition');
    Route::delete("/orders/{id}/position/{positionId}", 'OrderController@destroyPosition');
    Route::put("/orders/{id}/position/{positionId}", 'OrderController@updatePosition');
    Route::post("/orders/{id}/invoice/generate", 'OrderController@generateInvoiceNumber');
    Route::get("/orders/getAvailableOrderStates", 'OrderController@getAvailableOrderStates');
    Route::get("/orders/count", 'OrderController@countOrders');
    Route::resource('orders', 'OrderController');
    Route::get("/mechanics/count", 'MechanicController@countOrders');
    Route::resource('mechanics', 'MechanicController');
    Route::get("/allegro/api/search", "AllegroApiController@search");

});


