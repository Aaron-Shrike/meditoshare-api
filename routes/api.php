<?php

use App\Http\Controllers\AnuncioController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\DistritoController;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UsuarioController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Rutas API

//Ruta para iniciar sesion
Route::post('/iniciar-sesion', [UsuarioController::class, 'IniciarSesion']);

Route::get('/obtener-departamentos', [DepartamentoController::class, 'ObtenerDepartamentos']);
Route::get('/obtener-provincias/{departamento}', [ProvinciaController::class, 'ObtenerProvincias']);
Route::get('/obtener-distritos/{provincia}', [DistritoController::class, 'ObtenerDistritos']);

Route::post('/crear-usuario', [UsuarioController::class, 'CrearUsuario']);

// Route::middleware(['auth:sanctum'])->group(function () {
    // USUARIO
    Route::get('/obtener-usuario/{dni}', [UsuarioController::class, 'ObtenerUsuario']);
    Route::post('/modificar-usuario/{dni}', [UsuarioController::class, 'ModificarUsuario']);
    // ANUNCIOS
    Route::get('/obtener-anuncios', [AnuncioController::class, 'ObtenerAnuncios']);
    Route::post('/crear-anuncio/{dni}', [AnuncioController::class, 'CrearAnuncio']);
    Route::get('/mostrar-anuncios/{dni}', [AnuncioController::class, 'MostrarAnuncios']);
    // SOLICITUDES
    Route::post('/mostrar-solicitudes', [SolicitudController::class, 'MostrarSolicitudes']);
    Route::post('/mostrar-solicitudes-anuncio', [SolicitudController::class, 'MostrarSolicitudesAnuncio']);
    // PERFIL - SOLICITUD
    Route::post('/obtener-perfil-usuario', [UsuarioController::class, 'ObtenerPerfilUsuario']);
    Route::post('/obtener-recetas-usuario', [RecetaController::class, 'ObtenerRecetaUsuario']);
    Route::post('/obtener-diagnosticos-usuario', [DiagnosticoController::class, 'ObtenerDiagnosticoUsuario']);
    Route::post('/obtener-calificaciones-usuario', [SolicitudController::class, 'ObtenerCalificacionesUsuario']);
// });


