<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receta', function (Blueprint $table) {
            $table->bigInteger('id_receta')->unsigned()->autoIncrement();

            $table->char('dni', 8);
            $table->string('nombre_receta', 70);
            $table->string('url_receta', 255);
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
        Schema::dropIfExists('receta');
    }
}
