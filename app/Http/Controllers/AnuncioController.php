<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use Illuminate\Http\Request;

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
                Anuncio::select('id_anuncio', 'fecha_anuncio','nombre', 'descripcion', 
                    'concentracion', 'presentacion', 'fecha_vencimiento', 'cantidad', 'requiere_receta',
                    'requiere_diagnostico')
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
    public function MostrarAnuncios($dni)
    {
        try
        {
            $consulta = 
                Anuncio::select('id_anuncio', 'fecha_anuncio','nombre', 'descripcion', 
                    'concentracion', 'presentacion', 'fecha_vencimiento', 'cantidad', 'requiere_receta',
                    'requiere_diagnostico')
                ->where('dni_donante', '=', $dni)
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
