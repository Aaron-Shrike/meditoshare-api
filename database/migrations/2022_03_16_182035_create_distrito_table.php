<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistritoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distrito', function (Blueprint $table) {
            $table->smallInteger('id_distrito')->unsigned()->autoIncrement();
            
            $table->smallInteger('id_provincia')->unsigned();
            $table->string('descripcion', 40);
            $table->timestamps();

            $table->foreign('id_provincia')
                ->references('id_provincia')
                ->on('provincia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distrito');
    }
}
