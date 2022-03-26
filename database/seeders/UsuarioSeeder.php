<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Usuario 1 - 20 Anuncios
        $obj = new Usuario();
        $obj->dni = "12345671";
        $obj->id_distrito = 1;
        $obj->id_provincia = 1;
        $obj->id_departamento = 1;
        $obj->nombre = "Aarón";
        $obj->apellido_paterno = "Rojas";
        $obj->apellido_materno = "Vera";
        $obj->fecha_nacimiento = "2000-12-23";
        $obj->direccion = "Asent. H. Las maravillas Mz. H Lt. 05";
        $obj->telefono = "978488529";
        $obj->correo = "aaronrv138@gmail.com";
        $obj->contrasenia = Hash::make("12345678");
        $obj->save();
        // Usuario 2
        $obj = new Usuario();
        $obj->dni = "12345672";
        $obj->id_distrito = 1;
        $obj->id_provincia = 1;
        $obj->id_departamento = 1;
        $obj->nombre = "Rafael";
        $obj->apellido_paterno = "Ramirez";
        $obj->apellido_materno = "Benites";
        $obj->fecha_nacimiento = "2000-12-01";
        $obj->direccion = "Av. Ramon Castilla 01";
        $obj->telefono = "978488529";
        $obj->correo = "rramirezb@unprg.edu.pe";
        $obj->contrasenia = Hash::make("12345678");
        $obj->save();
        // Usuario 3
        $obj = new Usuario();
        $obj->dni = "12345673";
        $obj->id_distrito = 1;
        $obj->id_provincia = 1;
        $obj->id_departamento = 1;
        $obj->nombre = "Daniela";
        $obj->apellido_paterno = "Vilas";
        $obj->apellido_materno = "Fernandez";
        $obj->fecha_nacimiento = "2000-12-02";
        $obj->direccion = "Pr. Garcilazo de la vega n.4";
        $obj->telefono = "975487526";
        $obj->correo = "dvilasf@unprg.edu.pe";
        $obj->contrasenia = Hash::make("12345678");
        $obj->save();
    }
}
