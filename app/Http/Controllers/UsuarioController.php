<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    //Inicio de sesion
    public function IniciarSesion(Request $request)
    {
        try
        {
            $request->validate([
                'dni' => 'required',
                'contrasenia' => 'required',
            ]);
            
            $data=array();

            $consulta = Usuario::select('dni', 'contrasenia')
                            ->where('dni','=',$request->dni)
                            ->first();

            if(isset($consulta['dni']))
            {
                if(Hash::check($request->contrasenia, $consulta['contrasenia']))
                {
                    $consulta = 
                        Usuario::select('nombre', 'apellido_paterno AS apellidoPaterno', 
                            'apellido_materno AS apellidoMaterno', 'dni', 'fecha_nacimiento AS fechaNacimiento',
                            'usuario.id_departamento AS codigoDepartamento', 
                            'departamento.descripcion AS departamento', 'usuario.id_provincia AS codigoProvincia', 
                            'provincia.descripcion AS provincia', 'usuario.id_distrito AS codigoDistrito', 
                            'distrito.descripcion AS distrito', 'direccion', 'telefono', 'correo')
                        ->join('departamento', 'usuario.id_departamento', '=', 'departamento.id_departamento')
                        ->join('provincia', 'usuario.id_provincia', '=', 'provincia.id_provincia')
                        ->join('distrito', 'usuario.id_distrito', '=', 'distrito.id_distrito')
                        ->where('dni','=',$request->dni)
                        ->first();
                    
                    $formato_fecha = date("d/m/Y", strtotime($consulta['fechaNacimiento']));

                    $data = $consulta;

                    $data['formatoFecha'] = $formato_fecha;
                }
                else
                {
                    $data = [
                        'error' => true,
                        'mensaje' => "Credenciales incorrectas."
                    ];
                }
            }
            else
            {
                $data = [
                    'error' => false,
                    'mensaje' => "Usuario no registrado."
                ];
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function CrearUsuario(Request $request)
    {
        try
        {
            $request->validate([
                'nombre' => 'required',
                'apellidoPaterno' => 'required',
                'apellidoMaterno' => 'required',
                'dni' => 'required',
                'fechaNacimiento' => 'required',
                'departamento' => 'required',
                'provincia' => 'required',
                'distrito' => 'required',
                'direccion' => 'required',
                'telefono' => 'required',
                'correo' => 'required',
                'contrasenia' => 'required',
            ]);

            $obj = new Usuario();
            $obj->nombre = $request->nombre;
            $obj->apellido_paterno = $request->apellidoPaterno;
            $obj->apellido_materno = $request->apellidoMaterno;
            $obj->dni = $request->dni;
            $obj->fecha_nacimiento = $request->fechaNacimiento;
            $obj->id_departamento = $request->departamento;
            $obj->id_provincia = $request->provincia;
            $obj->id_distrito = $request->distrito;
            $obj->direccion = $request->direccion;
            $obj->telefono = $request->telefono;
            $obj->correo = $request->correo;
            $obj->contrasenia = Hash::make($request->contrasenia);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ObtenerPerfilSolicitante(Request $request)
    {
        try
        {
            $request->validate([
                'dniUsuario' => 'required',
                'dniSolicitante' => 'required',
            ]);
            
            $data=array();

            $consulta = 
                Solicitud::join('anuncio', 'solicitud.id_anuncio', '=', 'anuncio.id_anuncio')
                ->where('dni_solicitante','=', $request['dniSolicitante'])
                ->where('anuncio.dni_donante','=', $request['dniUsuario'])
                ->where('id_estado','=', 1)
                ->count();

            if($consulta > 0)
            {
                $consulta = 
                    Usuario::select('nombre', 'apellido_paterno AS apellidoPaterno', 
                        'apellido_materno AS apellidoMaterno', 'dni', 'fecha_nacimiento AS fechaNacimiento',
                        'departamento.descripcion AS departamento', 'provincia.descripcion AS provincia',
                        'distrito.descripcion AS distrito', 'direccion', 'telefono', 'correo')
                    ->join('departamento', 'usuario.id_departamento', '=', 'departamento.id_departamento')
                    ->join('provincia', 'usuario.id_provincia', '=', 'provincia.id_provincia')
                    ->join('distrito', 'usuario.id_distrito', '=', 'distrito.id_distrito')
                    ->where('dni','=', $request['dniSolicitante'])
                    ->first();
                
                $formato_fecha = date("d/m/Y", strtotime($consulta['fechaNacimiento']));

                $data = $consulta;

                $data['formatoFecha'] = $formato_fecha;
            }
            else
            {
                $data = [
                    'error' => false,
                    'mensaje' => "El usuario no tiene solicitudes pendientes."
                ];
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ModificarUsuario(Request $request, $dni)
    {
        try
        {
            $request->validate([
                'departamento' => 'required',
                'provincia' => 'required',
                'distrito' => 'required',
                'direccion' => 'required',
                'telefono' => 'required',
                'correo' => 'required',
                'contrasenia' => 'required',
            ]);

            $consulta = Usuario::where('dni', '=', $dni)->first();

            $consulta->id_departamento = $request->departamento;
            $consulta->id_provincia = $request->provincia;
            $consulta->id_distrito = $request->distrito;
            $consulta->direccion = $request->direccion;
            $consulta->telefono = $request->telefono;
            $consulta->correo = $request->correo;
            $consulta->contrasenia = Hash::make($request->contrasenia);

            $consulta->save();

            return response("OK");
            // return response($consulta);
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
