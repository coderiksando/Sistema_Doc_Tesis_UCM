<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivoPDFTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivopdf', function (Blueprint $table) {
            $table->id('id')->unsigned();
            $table->bigInteger('id_fit')->unsigned();
            $table->string('path');
            $table->string('filename');
            $table->enum('tipo_pdf',['acta','avance_t','final_t', 'constancia_t'])->default('avance_t');


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
        Schema::dropIfExists('archivopdf');
    }
}
