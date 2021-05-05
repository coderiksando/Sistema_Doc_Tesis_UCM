<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFitAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fit_area', function (Blueprint $table) {
            $table->bigInteger('id_area')->unsigned()->required();
            $table->bigInteger('id_fit')->unsigned()->required();
            $table->timestamps();
            $table->primary(['id_area', 'id_fit']);
            $table->foreign('id_area')->references('id')->on('areatesis');
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
        Schema::dropIfExists('fit_area');
    }
}
