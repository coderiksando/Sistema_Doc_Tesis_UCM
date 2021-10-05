<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersEscuelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_escuelas', function (Blueprint $table) {
            $table->bigInteger('id_user')->unsigned()->required();
            $table->bigInteger('id_escuela')->unsigned()->required();
            $table->timestamps();

            $table->primary(['id_user', 'id_escuela']);
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->foreign('id_escuela')->references('id')->on('escuelas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_escuelas');
    }
}
