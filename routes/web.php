<?php

use App\Http\Controllers\AbonoFacturaController;
use App\Http\Controllers\FacturaCompraController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('api')->group(function(){


    //Rutas Automaticas RESTful
    Route::resource('/abonofactura', AbonoFacturaController::class,['except'=>['create','edit']]);
    Route::resource('/facturacompra', FacturaCompraController::class,['except'=>['create','edit']]);
});
