<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableFit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fit', function (Blueprint $table){
            $table->date('fecha')->default('2021-01-01');
            $table->integer('privado')->nullable()->default(0);
            $table->date('fecha_publicacion')->default('0001-01-01');
            $table->string('motivo_privado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fit', function (Blueprint $table) {
            $table->dropColumn('fecha');
            $table->dropColumn('privado');
            $table->dropColumn('fecha_publicacion');
            $table->dropColum('motivo_privado');
            
        });
    }
}
