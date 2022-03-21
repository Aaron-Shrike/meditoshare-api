<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ObtenerSolicitudes(Request $request)
    {
        try
        {
            $request->validate([
                'dni' => 'required',
            ]);

            $consulta = 
                Solicitud::select('id_anuncio AS codigoAnuncio', 'id_estado AS codigoEstado', 
                    'fecha_solicitud AS fechaSolicitud','fecha_estado AS fechaEstado', 
                    'motivo_rechazo AS motivoRechazo', 'puntaje', 'comentario')
                ->where('dni_solicitante', '=', $request->dni)
                ->orderBy('fecha_solicitud', 'DESC')
                ->take(10)->get();
            
            return response($consulta);
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }

    public function ObtenerCalificacionesSolicitante(Request $request)
    {
        try
        {
            $request->validate([
                'dniSolicitante' => 'required',
            ]);

            $data = array();

            $consulta = 
                Solicitud::select('solicitud.id_anuncio AS codigoAnuncio', 'fecha_estado AS fechaEstado', 
                    'puntaje','comentario', 'anuncio.nombre AS nombreMedicamento')
                ->join('anuncio', 'solicitud.id_anuncio', '=', 'anuncio.id_anuncio')
                ->where('dni_solicitante', '=', $request->dniSolicitante)
                ->where('id_estado','=', 3)
                ->orderBy('fecha_estado', 'DESC')
                ->take(10)->get();

            $consulta2 = Solicitud::avg('puntaje');

            $data = [
                'puntajePromedio' => $consulta2,
                'calificaciones' => $consulta
            ];
            
            return response($data);
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
