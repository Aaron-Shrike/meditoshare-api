<?php

namespace Database\Seeders;

use App\Models\Diagnostico;
use Illuminate\Database\Seeder;

class DiagnosticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Usuario 1
        $obj = new Diagnostico();
        $obj->dni = "12345671";
        $obj->nombre_diagnostico = "Paracetamol 1";
        $obj->url_diagnostico = "usuario1-diagnostico1.jpg";
        $obj->save();
    }
}
