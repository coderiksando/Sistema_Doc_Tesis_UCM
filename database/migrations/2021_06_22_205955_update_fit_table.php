<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Fit;

class UpdateFitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fit', function (Blueprint $table) {
            $table->bigInteger('id_escuela')->unsigned()->nullable()->after('id_p_co_guia');
            $table->foreign('id_escuela')->references('id')->on('escuelas');
        });

        Fit::all()->map(function($item, $key){
            $item->id_escuela = $item->User_P_Guia->id_escuela;
            $item->save();
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
            $table->dropColumn('id_escuela');
        });
    }
}
