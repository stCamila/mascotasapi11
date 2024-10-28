<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiposController;
use App\Http\Controllers\MascotasController;
use App\Http\Controllers\AuthController;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::post('auth/register',[AuthController::class,'create']);
Route::post('auth/login',[AuthController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::resource('tipos', TiposController::class);
    Route::resource('mascotas', MascotasController::class);
    Route::get('mascotasall', [MascotasController::class, 'getAllMascotas']);
    Route::get('mascotasbytipos',[MascotasController::class,'MascotasByTipos']);
    Route::get('auth/logout',[AuthController::class,'logout']);
});