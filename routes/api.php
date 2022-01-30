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

Route::put('registrar', [UsuariosController::class, 'registrar']);
Route::put('login', [UsuariosController::class, 'login']);
Route::put('RecuperarPass', [UsuariosController::class, 'RecuperarPass']);

Route::put('buscarycomprar', [VentasController::class, 'buscarycomprar']);

Route::middleware(["UserNoAdmin"])->group(function () {
    Route::put('vender', [VentasController::class, 'vender']);
    Route::put('buscar', [VentasController::class, 'buscar']);
});

Route::middleware(["UserAdmin"])->group(function () {
    Route::put('CrearCarta', [cartasController::class, 'CrearCarta']);
    Route::put('CrearColecion', [cartasController::class, 'CrearColecion']);
    Route::put('AñadirCarta', [cartasController::class, 'AñadirCarta']);
});
