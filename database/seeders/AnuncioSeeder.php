<?php

namespace Database\Seeders;

use App\Models\Anuncio;
use Illuminate\Database\Seeder;

class AnuncioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Anuncio 1
        $obj = new Anuncio();
        $obj->dni_donante = "12345672";
        $obj->fecha_anuncio = "2022-03-16 05:07:41";
        $obj->fecha_vencimiento = "2022-08-16";
        $obj->nombre = "Paracetamol";
        $obj->presentacion = "tableta";
        $obj->concentracion = "50";
        $obj->descripcion = "estan prrs";
        $obj->cantidad = "20";
        $obj->requiere_receta = 1;
        $obj->requiere_diagnostico = 1;
        $obj->activo = 1;
        $obj->save();
    }
}
