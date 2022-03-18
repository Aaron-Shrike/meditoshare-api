<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosticoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diagnostico', function (Blueprint $table) {
            $table->bigInteger('id_diagnostico')->unsigned()->autoIncrement();

            $table->char('dni', 8);
            $table->string('nombre_diagnostico',70); //ver
            $table->string('url_diagnostico'); //ver
            $table->timestamps();

            $table->foreign('dni')
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
        Schema::dropIfExists('diagnostico');
    }
}
