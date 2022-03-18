<?php

namespace App\Http\Controllers;

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
                        Usuario::select('nombre', 'apellido_paterno', 'apellido_materno', 
                            'dni', 'fecha_nacimiento', 'usuario.id_departamento', 
                            'departamento.descripcion AS departamento', 'usuario.id_provincia', 
                            'provincia.descripcion AS provincia', 'usuario.id_distrito', 
                            'distrito.descripcion AS distrito', 'direccion', 'telefono', 'correo')
                        ->join('departamento', 'usuario.id_departamento', '=', 'departamento.id_departamento')
                        ->join('provincia', 'usuario.id_provincia', '=', 'provincia.id_provincia')
                        ->join('distrito', 'usuario.id_distrito', '=', 'distrito.id_distrito')
                        ->where('dni','=',$request->dni)
                        ->first();
                    
                    $formato_fecha = date("d/m/Y", strtotime($consulta['fecha_nacimiento']));

                    $data = [
                        'nombre' => $consulta['nombre'],
                        'apellidoPaterno' => $consulta['apellido_paterno'],
                        'apellidoMaterno' => $consulta['apellido_materno'],
                        'dni' => $consulta['dni'],
                        'fechaNacimiento' => $consulta['fecha_nacimiento'],
                        'formatoFechaNacimiento' => $formato_fecha,
                        'codigoDepartamento' => $consulta['id_departamento'],
                        'departamento' => $consulta['departamento'],
                        'codigoProvincia' => $consulta['id_provincia'],
                        'provincia' => $consulta['provincia'],
                        'codigoDistrito' => $consulta['id_distrito'],
                        'distrito' => $consulta['distrito'],
                        'direccion' => $consulta['direccion'],
                        'telefono' => $consulta['telefono'],
                        'correo' => $consulta['correo'],
                    ];
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
