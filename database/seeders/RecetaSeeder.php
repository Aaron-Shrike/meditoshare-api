<?php

namespace Database\Seeders;

use App\Models\Receta;
use Illuminate\Database\Seeder;

class RecetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Usuario 1
        $obj = new Receta();
        $obj->dni = "12345671";
        $obj->nombre_receta = "Paracetamol 1";
        $obj->url_receta = "usuario1-receta1.jpg";
        $obj->save();
        $obj = new Receta();
        $obj->dni = "12345671";
        $obj->nombre_receta = "Paracetamol 2";
        $obj->url_receta = "usuario1-receta2.jpg";
        $obj->save();
        $obj = new Receta();
        $obj->dni = "12345671";
        $obj->nombre_receta = "Paracetamol 3";
        $obj->url_receta = "usuario1-receta3.jpg";
        $obj->save();
        $obj = new Receta();
        $obj->dni = "12345671";
        $obj->nombre_receta = "Paracetamol 4";
        $obj->url_receta = "usuario1-receta4.jpg";
        $obj->save();
        $obj = new Receta();
        $obj->dni = "12345671";
        $obj->nombre_receta = "Paracetamol 5";
        $obj->url_receta = "usuario1-receta5.jpg";
        $obj->save();
    }
}
