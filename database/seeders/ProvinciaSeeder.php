<?php

namespace Database\Seeders;

use App\Models\Provincia;
use Illuminate\Database\Seeder;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $obj = new Provincia();
        $obj->id_departamento = 1;
        $obj->descripcion = "Chiclayo";
        $obj->save();
    }
}
