<?php

namespace App\Http\Controllers;

use App\Models\Diagnostico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiagnosticoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ObtenerDiagnosticos(Request $request)
    {
        try
        {
            $request->validate([
                'dni' => 'required',
            ]);

            $consulta = Diagnostico::select('id_diagnostico AS codigoDiagnostico', 'nombre_diagnostico AS nombreDiagnostico', 'url_diagnostico AS urlDiagnostico')
                        ->where('dni', '=', $request->dni)
                        ->get();

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
    public function CrearDiagnostico(Request $request)
    {
        try
        {
            $request->validate([
                'dniUsuario' => 'required',
                'nombre' => 'required',
                'archivo' => 'required',
                // 'archivo' => 'image|mimes:jpg,jpeg|max:2048',
            ]);

            $data = array();

            $obj = new Diagnostico();
            $obj->dni = $request->dniUsuario;
            $obj->nombre_diagnostico = $request->nombre;
            $obj->url_diagnostico = 'none'; 
            if($request->hasFile("archivo")){
                $imagen = $request->file("archivo");
                $nueva_url_archivo = $request->dniUsuario . "-" . time() . "-diagnostico" . "." . $imagen->guessExtension();

                $directorio_usuario = strval($request->dniUsuario);
                if(!Storage::disk('diagnostico')->exists($directorio_usuario))
                {
                    Storage::makeDirectory($directorio_usuario);
                }
                
                $ruta = public_path("diagnosticos/" . $directorio_usuario . "/");
                $imagen->move($ruta, $nueva_url_archivo);
    
                $obj->url_diagnostico = $nueva_url_archivo; 
            }

            $obj->save();

            $data = [
                'codigoDiagnosticota' => $obj->id_diagnostico,
                'urlDiagnostico' => $obj->url_diagnostico
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function BorrarDiagnostico(Request $request)
    {
        try
        {
            $request->validate([
                'codigoDiagnostico' => 'required',
                'dniUsuario' => 'required',
                'urlArchivo' => 'required',
            ]);

            $path = 'diagnosticos/'. $request->dniUsuario . '/' . $request->urlArchivo;
            Storage::delete($path);

            Diagnostico::destroy($request->codigoDiagnostico);

            return response("Su diagnostico se elemino correctamente.");
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }
}
