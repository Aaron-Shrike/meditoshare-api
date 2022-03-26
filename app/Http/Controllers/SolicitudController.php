<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\Solicitud;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function CrearSolicitudAnuncio(Request $request)
    {
        try
        {
            $request->validate([
                'codigoAnuncio' => 'required',
                'dniSolicitante' => 'required',
            ]);

            $data = array();

            $consulta = 
                Anuncio::select('dni_donante AS dniDonante')
                ->where('id_anuncio', '=', $request->codigoAnuncio)
                ->first();

            if($consulta['dniDonante'] != $request['dniSolicitante'])
            {
                $consulta2 = 
                    Solicitud::where('id_anuncio', '=', $request->codigoAnuncio)
                    ->where('dni_solicitante', '=', $request->dniSolicitante)
                    ->where('id_estado', '=', 1)
                    ->count();

                if($consulta2 == 0)
                {
                    $obj = new Solicitud();
                    $obj->id_anuncio = $request->codigoAnuncio;
                    $obj->dni_solicitante = $request->dniSolicitante;
                    $obj->fecha_solicitud = new DateTime();
                    $obj->id_estado = 1;
                    $obj->fecha_estado = new DateTime();

                    $obj->save();

                    $data = "OK";
                }
                else
                {
                    $data['error'] = true;
                    $data['mensaje'] = "Ya tiene una solicitud registrada.";
                }
            }
            else
            {
                $data['error'] = true;
                $data['mensaje'] = "No puede solicitar su anuncio.";
            }

            return response($data);
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }

    public function AprobarSolicitudAnuncio(Request $request)
    {
        try
        {
            $request->validate([
                'codigoAnuncio' => 'required',
                'dniSolicitante' => 'required',
            ]);

            $data = array();

            $consulta = 
                Solicitud::select()
                ->where('dni_solicitante', '=', $request->dniSolicitante)
                ->where('id_anuncio', '=', $request->codigoAnuncio)
                ->first();

            if(isset($consulta['dni_solicitante']))
            {
                $consulta->id_estado = 2;
                $consulta->fecha_estado = new DateTime();

                $consulta->save();
            }
            else
            {
                $data['error'] = true;
                $data['mensaje'] = "No existe solicitud.";
            }

            return response($data);
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }

    public function RechazarSolicitudAnuncio(Request $request)
    {
        try
        {
            $request->validate([
                'codigoAnuncio' => 'required',
                'dniSolicitante' => 'required',
                'motivo' => 'required',
            ]);

            $data = array();

            $consulta = 
                Solicitud::select()
                ->where('dni_solicitante', '=', $request->dniSolicitante)
                ->where('id_anuncio', '=', $request->codigoAnuncio)
                ->first();

            if(isset($consulta['dni_solicitante']))
            {
                $consulta->id_estado = 3;
                $consulta->fecha_estado = new DateTime();
                $consulta->motivo_rechazo = $request['motivo'];

                $consulta->save();
            }
            else
            {
                $data['error'] = true;
                $data['mensaje'] = "No existe solicitud.";
            }

            return response($data);
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }

    public function CalificarSolicitudAnuncio(Request $request)
    {
        try
        {
            $request->validate([
                'codigoAnuncio' => 'required',
                'dniSolicitante' => 'required',
                'puntaje' => 'required',
                'comentario' => 'required',
            ]);

            $data = array();

            $consulta = 
                Solicitud::select()
                ->where('dni_solicitante', '=', $request->dniSolicitante)
                ->where('id_anuncio', '=', $request->codigoAnuncio)
                ->first();

            if(isset($consulta['dni_solicitante']))
            {
                $consulta->id_estado = 4;
                $consulta->puntaje = $request['puntaje'];
                $consulta->comentario = $request['comentario'];
                $consulta->fecha_estado = new DateTime();

                $consulta->save();
            }
            else
            {
                $data['error'] = true;
                $data['mensaje'] = "No existe solicitud.";
            }

            return response($data);
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
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
                Solicitud::select('anuncio.id_anuncio AS codigoAnuncio', 'dni_donante AS dniDonante',
                'fecha_anuncio AS fechaAnuncio', 
                    DB::raw('date_format(fecha_anuncio, "%d/%m/%Y") AS formatoFechaAnuncio'),
                    'anuncio.nombre', 'anuncio.descripcion', 'concentracion', 'presentacion', 
                    'fecha_vencimiento AS fechaVencimiento','cantidad', 'activo',
                    DB::raw('date_format(fecha_vencimiento, "%d/%m/%Y") AS formatoFechaVencimiento'),
                    'requiere_receta AS requiereReceta', 'requiere_diagnostico AS requiereDiagnostico',
                    'departamento.descripcion AS departamento', 'distrito.descripcion AS distrito',
                    'motivo_rechazo AS motivo', 'id_estado AS codigoEstado')
                ->join('anuncio', 'solicitud.id_anuncio', '=', 'anuncio.id_anuncio')
                ->join('usuario', 'anuncio.dni_donante', '=', 'usuario.dni')
                ->join('departamento', 'usuario.id_departamento', '=', 'departamento.id_departamento')
                ->join('distrito', 'usuario.id_distrito', '=', 'distrito.id_distrito')
                ->where('dni_solicitante', '=', $request['dni'])
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

    public function ObtenerSolicitudesAnuncio(Request $request)
    {
        try
        {
            $request->validate([
                'codigoAnuncio' => 'required',
            ]);

            $consulta =
                Solicitud::select('id_anuncio AS codigoAnuncio', 'dni_solicitante AS dniSolicitante',
                    'nombre', 'apellido_paterno AS apellidoPaterno', 'apellido_materno AS apellidoMaterno',
                    'fecha_solicitud AS fechaSolicitud', 
                    DB::raw('date_format(fecha_solicitud, "%d/%m/%Y") AS formatoFechaSolicitud'),
                    'solicitud.id_estado AS codigoEstado', 'descripcion AS estado', 
                    'motivo_rechazo AS motivo')
                ->join('usuario', 'solicitud.dni_solicitante', '=', 'usuario.dni')
                ->join('estado_solicitud', 'solicitud.id_estado', '=', 'estado_solicitud.id_estado')
                ->where('id_anuncio', '=', $request['codigoAnuncio'])
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
                ->where('id_estado','=', 4)
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
