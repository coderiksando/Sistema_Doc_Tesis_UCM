<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableNotaspendientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notaspendientes', function (Blueprint $table){
            $table->bigInteger('id_alumno')->nullable()->unsigned()->after('id_tesis');
            $table->foreign('id_alumno')->references('id_user')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notaspendientes', function (Blueprint $table) {
            $table->dropForeign('notaspendientes_id_alumno_foreign');
            $table->dropColumn('id_alumno');

        });
    }
}
