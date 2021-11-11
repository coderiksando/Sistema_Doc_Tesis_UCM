<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableEscuelaAbreviacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('escuelas', function (Blueprint $table){
            $table->string('nombre_abreviado')->default('S/A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('escuelas', function (Blueprint $table) {
            $table->dropColumn('nombre_abreviado');
        });
    }
}
