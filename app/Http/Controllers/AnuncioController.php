<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnuncioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ObtenerAnuncios()
    {
        try
        {
            $consulta = 
            Anuncio::select('id_anuncio AS codigoAnuncio', 'fecha_anuncio AS fechaAnuncio',
                DB::raw('date_format(fecha_anuncio, "%d/%m/%Y") AS formatoFechaAnuncio'),
                'anuncio.nombre', 'anuncio.descripcion', 'concentracion', 'presentacion', 
                'fecha_vencimiento AS fechaVencimiento','cantidad', 
                DB::raw('date_format(fecha_vencimiento, "%d/%m/%Y") AS formatoFechaVencimiento'),
                'requiere_receta AS requiereReceta', 'requiere_diagnostico AS requiereDiagnostico', 
                'departamento.descripcion AS departamento', 'distrito.descripcion AS distrito')
            ->join('usuario', 'anuncio.dni_donante', '=', 'usuario.dni')
            ->join('departamento', 'usuario.id_departamento', '=', 'departamento.id_departamento')
            ->join('distrito', 'usuario.id_distrito', '=', 'distrito.id_distrito')
            ->where('activo', '=', 1)
            ->orderBy('fecha_anuncio', 'DESC')
            ->take(10)->get();
            
            return response($consulta);
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }

    public function ObtenerAnunciosFechaAscendente()
    {
        try
        {
            $consulta = 
            Anuncio::select('id_anuncio AS codigoAnuncio', 'fecha_anuncio AS fechaAnuncio',
                DB::raw('date_format(fecha_anuncio, "%d/%m/%Y") AS formatoFechaAnuncio'),
                'anuncio.nombre', 'anuncio.descripcion', 'concentracion', 'presentacion', 
                'fecha_vencimiento AS fechaVencimiento','cantidad', 
                DB::raw('date_format(fecha_vencimiento, "%d/%m/%Y") AS formatoFechaVencimiento'),
                'requiere_receta AS requiereReceta', 'requiere_diagnostico AS requiereDiagnostico', 
                'departamento.descripcion AS departamento', 'distrito.descripcion AS distrito')
            ->join('usuario', 'anuncio.dni_donante', '=', 'usuario.dni')
            ->join('departamento', 'usuario.id_departamento', '=', 'departamento.id_departamento')
            ->join('distrito', 'usuario.id_distrito', '=', 'distrito.id_distrito')
            ->where('activo', '=', 1)
            ->orderBy('fecha_anuncio', 'ASC')
            ->take(10)->get();
            
            return response($consulta);
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }

    public function ObtenerAnunciosRequiereReceta($requiere)
    {
        try
        {
            $consulta = 
            Anuncio::select('id_anuncio AS codigoAnuncio', 'fecha_anuncio AS fechaAnuncio',
                DB::raw('date_format(fecha_anuncio, "%d/%m/%Y") AS formatoFechaAnuncio'),
                'anuncio.nombre', 'anuncio.descripcion', 'concentracion', 'presentacion', 
                'fecha_vencimiento AS fechaVencimiento','cantidad', 
                DB::raw('date_format(fecha_vencimiento, "%d/%m/%Y") AS formatoFechaVencimiento'),
                'requiere_receta AS requiereReceta', 'requiere_diagnostico AS requiereDiagnostico', 
                'departamento.descripcion AS departamento', 'distrito.descripcion AS distrito')
            ->join('usuario', 'anuncio.dni_donante', '=', 'usuario.dni')
            ->join('departamento', 'usuario.id_departamento', '=', 'departamento.id_departamento')
            ->join('distrito', 'usuario.id_distrito', '=', 'distrito.id_distrito')
            ->where('requiere_receta', '=', $requiere)
            ->where('activo', '=', 1)
            ->orderBy('fecha_anuncio', 'DESC')
            ->take(10)->get();
            
            return response($consulta);
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }

    public function ObtenerAnunciosRequiereDiagnostico($requiere)
    {
        try
        {
            $consulta = 
            Anuncio::select('id_anuncio AS codigoAnuncio', 'fecha_anuncio AS fechaAnuncio',
                DB::raw('date_format(fecha_anuncio, "%d/%m/%Y") AS formatoFechaAnuncio'),
                'anuncio.nombre', 'anuncio.descripcion', 'concentracion', 'presentacion', 
                'fecha_vencimiento AS fechaVencimiento','cantidad', 
                DB::raw('date_format(fecha_vencimiento, "%d/%m/%Y") AS formatoFechaVencimiento'),
                'requiere_receta AS requiereReceta', 'requiere_diagnostico AS requiereDiagnostico', 
                'departamento.descripcion AS departamento', 'distrito.descripcion AS distrito')
            ->join('usuario', 'anuncio.dni_donante', '=', 'usuario.dni')
            ->join('departamento', 'usuario.id_departamento', '=', 'departamento.id_departamento')
            ->join('distrito', 'usuario.id_distrito', '=', 'distrito.id_distrito')
            ->where('requiere_diagnostico', '=', $requiere)
            ->where('activo', '=', 1)
            ->orderBy('fecha_anuncio', 'DESC')
            ->take(10)->get();
            
            return response($consulta);
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }

    public function ObtenerAnunciosUsuario(Request $request)
    {
        try
        {
            $request->validate([
                'dni' => 'required',
            ]);

            $consulta = 
                Anuncio::select('anuncio.id_anuncio AS codigoAnuncio', 'fecha_anuncio AS fechaAnuncio', 
                    DB::raw('date_format(fecha_anuncio, "%d/%m/%Y") AS formatoFechaAnuncio'),
                    'anuncio.nombre', 'anuncio.descripcion', 'concentracion', 'presentacion', 
                    'fecha_vencimiento AS fechaVencimiento','cantidad', 'activo',
                    DB::raw('date_format(fecha_vencimiento, "%d/%m/%Y") AS formatoFechaVencimiento'),
                    'requiere_receta AS requiereReceta', 'requiere_diagnostico AS requiereDiagnostico',
                    'departamento.descripcion AS departamento', 'distrito.descripcion AS distrito',
                    DB::raw('COUNT(solicitud.id_anuncio) AS solicitudes'))
                ->join('usuario', 'anuncio.dni_donante', '=', 'usuario.dni')
                ->leftjoin('solicitud', 'anuncio.id_anuncio', '=', 'solicitud.id_anuncio')
                ->join('departamento', 'usuario.id_departamento', '=', 'departamento.id_departamento')
                ->join('distrito', 'usuario.id_distrito', '=', 'distrito.id_distrito')
                ->where('dni_donante', '=', $request['dni'])
                ->orderBy('fecha_anuncio', 'DESC')
                ->groupBy('anuncio.id_anuncio')
                ->take(10)->get();
            
            return response($consulta);
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function CrearAnuncio(Request $request)
    {
        try
        {
            $request->validate([
                'dniDonante' => 'required',
                'nombre' => 'required',
                'descripcion' => '',
                'concentracion' => 'required',
                'presentacion' => 'required',
                'cantidad' => 'required',
                'requiereReceta' => 'required',
                'requiereDiagnostico' => 'required',
                'fechaVencimiento' => 'required',
            ]);

            $obj = new Anuncio();
            $obj->dni_donante = $request->dniDonante;
            $obj->nombre = $request->nombre;
            $obj->descripcion = $request->descripcion;
            $obj->concentracion = $request->concentracion;
            $obj->presentacion = $request->presentacion;
            $obj->cantidad = $request->cantidad;
            $obj->requiere_receta = $request->requiereReceta;
            $obj->requiere_diagnostico = $request->requiereDiagnostico;
            $obj->fecha_vencimiento = $request->fechaVencimiento;
            $obj->fecha_anuncio = new DateTime();
            $obj->activo = true;

            $obj->save();

            return response("OK");
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

    public function FinalizarAnuncio(Request $request)
    {
        try
        {
            $request->validate([
                'codigoAnuncio' => 'required',
            ]);

            $consulta = Anuncio::where('id_anuncio', '=', $request->codigoAnuncio)->first();

            $consulta->activo = false;

            $consulta->save();

            return response("OK");
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
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
