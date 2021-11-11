<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\ArchivoPdf;

class AlterTableArchivoPdf3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archivopdf', function (Blueprint $table){
            $table->bigInteger('id_propietario')->unsigned()->nullable();
            $table->foreign('id_propietario')->references('id_user')->on('users');
        });

        $archivos = ArchivoPdf::all();

        foreach ($archivos as $archivo) {
            $archivo->id_propietario = $archivo->Fit->getAlumnos()->first()->id_user;
            $archivo->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archivopdf', function (Blueprint $table) {
            $table->dropForeign('archivopdf_id_propietario_foreign');
            $table->dropColumn('id_propietario');

        });
    }
}
