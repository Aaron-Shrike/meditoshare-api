<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnuncioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anuncio', function (Blueprint $table) {
            $table->id('id_anuncio');
            
            $table->char('dni_donante', 8);
            $table->dateTime('fecha_anuncio');
            $table->string('nombre', 50);
            $table->string('descripcion', 255);
            $table->string('concentracion', 20);
            $table->string('presentacion', 20);
            $table->date('fecha_vencimiento'); //ver
            $table->string('cantidad', 5); //ver
            $table->boolean('requiere_receta');
            $table->boolean('requiere_diagnostico');
            $table->boolean('activo'); // ver
            $table->timestamps();

            $table->foreign('dni_donante')
                ->references('dni')
                ->on('usuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anuncio');
    }
}
