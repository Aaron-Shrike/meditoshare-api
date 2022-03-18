<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->char('dni', 8)->unique(); //ver

            $table->smallInteger('id_distrito')->unsigned();
            $table->smallInteger('id_provincia')->unsigned();
            $table->tinyInteger('id_departamento')->unsigned();
            $table->string('nombre', 50);
            $table->string('apellido_paterno', 50);
            $table->string('apellido_materno', 50);
            $table->date('fecha_nacimiento'); //ver
            $table->string('direccion', 95);
            $table->string('telefono', 9); //ver
            $table->string('correo', 255)->unique();
            $table->string('contrasenia', 60);
            // $table->string('estado', 10);
            $table->timestamps();

            $table->primary('dni');

            $table->foreign('id_distrito')
                ->references('id_distrito')
                ->on('distrito');
            $table->foreign('id_provincia')
                ->references('id_provincia')
                ->on('provincia');
            $table->foreign('id_departamento')
                ->references('id_departamento')
                ->on('departamento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario');
    }
}
