<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\Solicitud;
use App\Models\Usuario;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Update the path below to your autoload.php, 
// see https://getcomposer.org/doc/01-basic-usage.md 
require base_path() . '/vendor/autoload.php'; 

use Twilio\Rest\Client; 

// $dotenv = Dotenv\Dotenv::create(base_path());
// $dotenv->load();

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
                'urgente' => 'required',
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

                    //enviar mensaje de wpp
                    if($request->urgente)
                    {
                        $consulta3 = 
                            Anuncio::select('telefono', 'anuncio.nombre AS nombreAnuncio', 'presentacion', 
                                'usuario.nombre AS nombreUsuario', 'apellido_paterno AS apellidoPaterno',
                                'apellido_materno AS apellidoMaterno')
                            ->join('usuario', 'anuncio.dni_donante', '=', 'usuario.dni')
                            ->where('id_anuncio', '=', $request->codigoAnuncio)
                            ->first();

                        $sid    = env('TWILIO_ACCOUNT_SID'); 
                        $token  = env('TWILIO_AUTH_TOKEN'); 
                        $twilio = new Client($sid, $token); 
                        
                        $contenido_mensaje = "Tiene una solicitud con carácter de URGENCIA.\nPor parte de " . $consulta3['nombreUsuario'] . " " . $consulta3['apellidoPaterno'] . " " . $consulta3['apellidoMaterno'] . " en su anuncio del medicamento ". $consulta3['nombreMedicamento'] . "(". $consulta3['presentacion'] . ").\nAcceda a su cuenta en https://meditoshare.netlify.app/\n\t- MediToShare -";

                        $message = $twilio->messages 
                                        ->create("whatsapp:+51".$consulta3['telefono'], // to 
                                                array( 
                                                    "from" => "whatsapp:".env('TWILIO_NUMBER'),       
                                                    "body" => $contenido_mensaje 
                                                ) 
                                        ); 
                        
                        $data = $message->sid;
                    }

                    // $data = "OK";
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

            //calificar solicitud
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

                //rechazar solicitudes de anuncio
                $consulta = Solicitud::where('id_anuncio', '=', $request->codigoAnuncio)
                    ->where('id_estado', '!=', 4)->get();

                foreach($consulta as $key => $fila)
                {
                    $fila->id_estado = 3;
                    $fila->motivo_rechazo = "Anuncio entregado a otro usuario";
                    $fila->save();
                }

                //finalizar anuncio
                $consulta = Anuncio::where('id_anuncio', '=', $request->codigoAnuncio)
                    ->update(['activo' => "0"]);
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

            $data = array();
            $pagina = $_GET['pagina'];

            $consulta = 
                Solicitud::select('id_anuncio')
                ->where('dni_solicitante', '=', $request['dni'])
                ->get();
            $total_solicitudes = $consulta->count();

            $paginas = ceil($total_solicitudes/10);

            $solicitud_inicial = ($pagina * 10) - 9 - 1;

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
                ->offset($solicitud_inicial)
                ->limit(10)->get();

            $data = [
                'pagina' => $pagina,
                'solicitudes' => $consulta,
                'totalPaginas' => $paginas,
                'totalSolicitudes' => $total_solicitudes,
            ];
            
            return response($data);
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

            $data = array();
            $pagina = $_GET['pagina'];

            $consulta = 
                Solicitud::select('id_anuncio')
                ->where('id_anuncio', '=', $request['codigoAnuncio'])
                ->get();
            $total_solicitudes = $consulta->count();

            $paginas = ceil($total_solicitudes/10);

            $solicitud_inicial = ($pagina * 10) - 9 - 1;

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
                ->offset($solicitud_inicial)
                ->limit(10)->get();

            $data = [
                'pagina' => $pagina,
                'solicitudes' => $consulta,
                'totalPaginas' => $paginas,
                'totalSolicitudes' => $total_solicitudes,
            ];
            
            return response($data);
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
            $pagina = $_GET['pagina'];

            $consulta = 
                Solicitud::select('id_anuncio')
                ->where('dni_solicitante', '=', $request['dniSolicitante'])
                ->where('id_estado','=', 4)
                ->get();
            $total_solicitudes = $consulta->count();

            $paginas = ceil($total_solicitudes/10);

            $solicitud_inicial = ($pagina * 10) - 9 - 1;

            $consulta = 
                Solicitud::select('solicitud.id_anuncio AS codigoAnuncio', 'fecha_estado AS fechaEstado', 
                    'puntaje','comentario', 'anuncio.nombre AS nombreMedicamento')
                ->join('anuncio', 'solicitud.id_anuncio', '=', 'anuncio.id_anuncio')
                ->where('dni_solicitante', '=', $request->dniSolicitante)
                ->where('id_estado','=', 4)
                ->orderBy('fecha_estado', 'DESC')
                ->offset($solicitud_inicial)
                ->limit(10)->get();

            $consulta2 = Solicitud::avg('puntaje');

            $data = [
                'pagina' => $pagina,
                'solicitudes' => [
                    'puntajePromedio' => $consulta2,
                    'calificaciones' => $consulta
                ],
                'totalPaginas' => $paginas,
                'totalSolicitudes' => $total_solicitudes,
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
