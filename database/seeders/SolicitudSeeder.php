<?php

namespace Database\Seeders;

use App\Models\Solicitud;
use Illuminate\Database\Seeder;

class SolicitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Solicitud 1
        $obj = new Solicitud();
        $obj->dni_solicitante = "12345673";
        $obj->id_anuncio = 1;
        $obj->id_estado = 1;
        $obj->fecha_solicitud = "2022-03-16 05:07:41";
        $obj->fecha_estado = "2022-03-16 05:07:41";
        // $obj->motivo_rechazo = "Otro";
        // $obj->puntaje = "1";
        // $obj->comentario = "me caes mal";
        $obj->save();
        // Solicitud 2
        $obj = new Solicitud();
        $obj->dni_solicitante = "12345673";
        $obj->id_anuncio = 2;
        $obj->id_estado = 1;
        $obj->fecha_solicitud = "2022-03-16 06:07:41";
        $obj->fecha_estado = "2022-03-16 06:07:41";
        $obj->save();
    }
}
