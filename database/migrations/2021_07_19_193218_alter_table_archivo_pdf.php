<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\DBAL\TimestampType;

class AlterTableArchivoPdf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `archivopdf` MODIFY tipo_pdf ENUM('acta','avance_t','final_t', 'constancia_t', 'general') DEFAULT 'general';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archivopdf', function (Blueprint $table) {
            //
        });
    }
}
