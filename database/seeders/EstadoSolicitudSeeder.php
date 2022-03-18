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
        $obj->descripcion = 'Activo';
        $obj->save();
        // Rechazado
        $obj = new EstadoSolicitud();
        $obj->descripcion = 'Rechazado';
        $obj->save();
        // Entregado
        $obj = new EstadoSolicitud();
        $obj->descripcion = 'Entregado';
        $obj->save();
    }
}
