<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisionComisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revision_comision', function (Blueprint $table) {
            $table->bigInteger('id_fit')->unsigned()->required();
            $table->bigInteger('id_user')->unsigned()->required();
            $table->bigInteger('id_archivo')->unsigned()->required();
            $table->string('tipo')->required();
            $table->string('comentario')->nullable();
            $table->timestamps();

            $table->primary(['id_fit', 'id_user', 'id_archivo']);
            $table->foreign('id_fit')->references('id')->on('fit');
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->foreign('id_archivo')->references('id')->on('archivopdf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revision_comision');
    }
}
