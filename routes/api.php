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
    // USUARIO - MI PERFIL
    Route::post('/obtener-usuario', [UsuarioController::class, 'ObtenerUsuario']);
    Route::post('/modificar-usuario/{dni}', [UsuarioController::class, 'ModificarUsuario']);
    // USUARIO - VERIFICACIONES 
    Route::post('/obtener-recetas', [RecetaController::class, 'ObtenerRecetas']);
    Route::post('/obtener-diagnosticos', [DiagnosticoController::class, 'ObtenerDiagnosticos']);
    Route::post('/crear-receta', [RecetaController::class, 'CrearReceta']);
    Route::post('/crear-diagnostico', [DiagnosticoController::class, 'CrearDiagnostico']);
    Route::post('/borrar-receta', [RecetaController::class, 'BorrarReceta']);
    Route::post('/borrar-diagnostico', [DiagnosticoController::class, 'BorrarDiagnostico']);
    // ANUNCIOS
    Route::get('/obtener-anuncios', [AnuncioController::class, 'ObtenerAnuncios']);
    Route::post('/crear-anuncio', [AnuncioController::class, 'CrearAnuncio']);
    Route::get('/obtener-anuncios/{dni}', [AnuncioController::class, 'ObtenerAnunciosUsuario']);
    // SOLICITUDES
    Route::post('/obtener-solicitudes', [SolicitudController::class, 'ObtenerSolicitudes']);
    Route::post('/obtener-solicitudes-anuncio', [SolicitudController::class, 'ObtenerSolicitudesAnuncio']);
    // PERFIL - SOLICITUD
    Route::post('/obtener-perfil-solicitante', [UsuarioController::class, 'ObtenerPerfilSolicitante']);
    Route::post('/obtener-recetas-solicitante', [RecetaController::class, 'ObtenerRecetas']);
    Route::post('/obtener-diagnosticos-solicitante', [DiagnosticoController::class, 'ObtenerDiagnosticos']);
    Route::post('/obtener-calificaciones-solicitante', [SolicitudController::class, 'ObtenerCalificacionesSolicitante']);
// });


