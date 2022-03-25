<?php

namespace Database\Seeders;

use App\Models\EstadoSolicitud;
use Illuminate\Database\Seeder;

class EstadoSolicitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Activo
        $obj = new EstadoSolicitud();
        $obj->descripcion = 'Registrada';
        $obj->save();
        // Activo
        $obj = new EstadoSolicitud();
        $obj->descripcion = 'Aceptada';
        $obj->save();
        // Rechazado
        $obj = new EstadoSolicitud();
        $obj->descripcion = 'Rechazada';
        $obj->save();
        // Entregado
        $obj = new EstadoSolicitud();
        $obj->descripcion = 'Entregada';
        $obj->save();
    }
}
