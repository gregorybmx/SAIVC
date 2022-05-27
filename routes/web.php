<?php

use App\Http\Controllers\AbonoFacturaController;
use App\Http\Controllers\AgenteVendedorController;
use App\Http\Controllers\FacturaVentaController;
use App\Http\Controllers\DetalleFacturaVentaController;
use App\Http\Controllers\FacturaCompraController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProveedoresProductosController;
use App\Http\Controllers\UserController;
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
Route::prefix('api')->group(function()
{
    Route::post('/user/upload',[UserController::class,'uploadImage']);
    Route::get('user/getimage/{filename}',[UserController::class, 'getImage']);
    Route::post('/user/login',[UserController::class, 'login']);
    Route::get('/user/getidentity',[UserController::class, 'getIdentity']);
    
    //Rutas Automaticas RESTful
    Route::resource('/facturaventa', FacturaVentaController::class,['except'=>['create','edit']]);
    Route::resource('/detallefacturaventa', DetalleFacturaVentaController::class,['except'=>['create','edit']]);
    Route::resource('/abonofactura', AbonoFacturaController::class,['except'=>['create','edit']]);
    Route::resource('/agentevendedor', AgenteVendedorController::class,['except'=>['create','edit']]);
    Route::resource('/facturacompra', FacturaCompraController::class,['except'=>['create','edit']]);
    Route::resource('/producto', ProductoController::class,['except'=>['create','edit']]);
    Route::resource('/proveedor', ProveedorController::class,['except'=>['create','edit']]);
    Route::resource('/proveedorproductos', ProveedoresProductosController::class,['except'=>['create','edit','update', 'show', 'index']]);
    Route::resource('/user', UserController::class,['except'=>['create','edit','login', 'getIdentity']]);
});
