<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvinciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provincia', function (Blueprint $table) {
            $table->smallInteger('id_provincia')->unsigned()->autoIncrement(); //ver
            
            $table->tinyInteger('id_departamento')->unsigned();
            $table->string('descripcion', 30);
            $table->timestamps();

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
        Schema::dropIfExists('provincia');
    }
}
