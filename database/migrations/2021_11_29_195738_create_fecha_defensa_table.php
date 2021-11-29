<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFechaDefensaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fecha_defensa', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_fit')->unsigned();
            $table->string('sala');
            $table->datetime('fecha');
            $table->timestamps();

            $table->foreign('id_fit')->references('id')->on('fit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fecha_defensa');
    }
}
