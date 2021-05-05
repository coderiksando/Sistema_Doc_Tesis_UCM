<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFitUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fit_user', function (Blueprint $table) {
            $table->bigInteger('id_user')->unsigned()->required();
            $table->bigInteger('id_fit')->unsigned()->required();
            $table->timestamps();
            $table->primary(['id_user', 'id_fit']);
            $table->foreign('id_user')->references('id_user')->on('users');
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
        Schema::dropIfExists('fit_user');
    }
}
