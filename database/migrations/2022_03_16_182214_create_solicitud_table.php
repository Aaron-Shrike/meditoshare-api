<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud', function (Blueprint $table) {
            $table->char('dni_solicitante', 8);
            $table->bigInteger('id_anuncio')->unsigned();
            
            $table->tinyInteger('id_estado')->unsigned();
            $table->dateTime('fecha_solicitud');
            $table->dateTime('fecha_estado');
            $table->string('motivo_rechazo', 255)->nullable();
            $table->char('puntaje', 1)->nullable();
            $table->string('comentario', 255)->nullable();
            $table->timestamps();

            $table->foreign('dni_solicitante')
                ->references('dni')
                ->on('usuario');
            $table->foreign('id_anuncio')
                ->references('id_anuncio')
                ->on('anuncio');
            $table->primary(['dni_solicitante', 'id_anuncio']);

            $table->foreign('id_estado')
                ->references('id_estado')
                ->on('estado_solicitud');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitud');
    }
}
