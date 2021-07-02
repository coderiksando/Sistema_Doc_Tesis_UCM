<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fit', function (Blueprint $table) {
            $table->id('id')->unsigned();
            // $table->bigInteger('id_alumno')->unsigned()->nullable();
            $table->bigInteger('id_vinculacion')->unsigned()->nullable();
            $table->bigInteger('id_p_guia')->unsigned();
            $table->bigInteger('id_p_co_guia')->unsigned()->nullable();
            $table->bigInteger('id_escuela')->unsigned()->nullable();
            // $table->bigInteger('id_pdftesis')->unsigned()->nullable();
            // $table->bigInteger('id_actadefensa')->unsigned()->nullable();
            $table->decimal('nota', 2, 1)->nullable();
            $table->enum('estado',['A','R','D'])->nullable()->default('D');
            $table->string('titulo')->nullable();
            $table->string('tipo')->nullable();
            // $table->date('fecha_ultimoramo')->nullable();
            $table->text('objetivo_general')->nullable();
            $table->text('objetivo_especifico')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('contribucion')->nullable();
            // $table->string('nombre_int1')->nullable();
            // $table->string('rut_int1')->nullable();
            // $table->string('email_int1')->nullable();
            // $table->string('ingreso_int1')->nullable();
            // $table->string('telefono_int1')->nullable();
            // $table->string('nombre_int2')->nullable();
            // $table->string('rut_int2')->nullable();
            // $table->string('email_int2')->nullable();
            // $table->string('ingreso_int2')->nullable();
            // $table->string('telefono_int2')->nullable();
            $table->enum('aprobado_pg',['A','R','P','V'])->nullable()->default('P');
            $table->text('motivo_pg')->nullable();

            // $table->foreign('id_alumno')->references('id_user')->on('alumno');
            $table->foreign('id_vinculacion')->references('id')->on('vinculaciones')->onDelete('set null');
            $table->foreign('id_p_guia')->references('id_user')->on('users');
            $table->foreign('id_p_co_guia')->references('id_user')->on('users');
            $table->foreign('id_escuela')->references('id')->on('escuelas');
            // $table->foreign('id_pdftesis')->references('id')->on('pdftesis')->onDelete('set null');
            // $table->foreign('id_actadefensa')->references('id')->on('pdftesis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fit');
    }
}
