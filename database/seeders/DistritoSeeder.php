<?php

namespace Database\Seeders;

use App\Models\Distrito;
use Illuminate\Database\Seeder;

class DistritoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $obj = new Distrito();
        $obj->id_provincia = 1;
        $obj->descripcion = "Chiclayo";
        $obj->save();
    }
}
