<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ObtenerRecetas(Request $request)
    {
        try
        {
            $request->validate([
                'dni' => 'required',
            ]);

            $consulta = Receta::select('id_Receta AS codigoReceta', 'nombre_receta AS nombreReceta', 'url_receta AS urlReceta')
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
    public function CrearReceta(Request $request)
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

            $obj = new Receta();
            $obj->dni = $request->dniUsuario;
            $obj->nombre_receta = $request->nombre;
            $obj->url_receta = 'none'; 
            if($request->hasFile("archivo")){
                $imagen = $request->file("archivo");
                $nueva_url_archivo = $request->dniUsuario . "-" . time() . "-receta" . "." . $imagen->guessExtension();

                $directorio_usuario = strval($request->dniUsuario);
                if(!Storage::disk('receta')->exists($directorio_usuario))
                {
                    Storage::makeDirectory($directorio_usuario);
                }
                
                // Storage::disk('receta')->put($nueva_url_archivo, $imagen);
                $ruta = public_path("recetas/" . $directorio_usuario . "/");
                $imagen->move($ruta, $nueva_url_archivo);
    
                $obj->url_receta = $nueva_url_archivo; 
            }

            $obj->save();

            $data = [
                'codigoReceta' => $obj->id_receta,
                'urlReceta' => $obj->url_receta
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
    public function BorrarReceta(Request $request)
    {
        try
        {
            $request->validate([
                'codigoReceta' => 'required',
                'dniUsuario' => 'required',
                'urlArchivo' => 'required',
            ]);

            $path = 'recetas/'. $request->dniUsuario . '/' . $request->urlArchivo;
            Storage::delete($path);

            Receta::destroy($request->codigoReceta);

            return response("Su receta se elemino correctamente.");
        }
        catch (\Exception $ex) 
        {
            $data = $ex->getMessage();
            
            return response($data, 400);
        }
    }
}
