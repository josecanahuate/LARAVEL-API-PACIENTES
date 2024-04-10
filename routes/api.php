<?php

use App\Http\Controllers\API\PacienteController;
use App\Http\Controllers\API\AutenticarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//sin resources
/* Route::get("/pacientes", [PacienteController::class, 'index']); //http://127.0.0.1:8000/api/pacientes
Route::post("/pacientes", [PacienteController::class, 'store']); //http://127.0.0.1:8000/api/pacientes
Route::get("/pacientes/{paciente}", [PacienteController::class, 'show']); //http://127.0.0.1:8000/api/pacientes/1
Route::put("/pacientes/{paciente}", [PacienteController::class, 'update']); //http://127.0.0.1:8000/api/pacientes/1
Route::delete("/pacientes/{paciente}", [PacienteController::class, 'destroy']); //http://127.0.0.1:8000/api/pacientes/1
 */


//Rutas Api Tokens
Route::post('registro', [AutenticarController::class, 'registro']);
Route::post('login', [AutenticarController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('logout', [AutenticarController::class, 'logout']); 
    Route::resource('/pacientes', PacienteController::class);
});

//si el usuario no esta registrado ni logeado no puede acceder a la ruta pacientes