<?php

use Illuminate\Database\Seeder;
use App\Parametro;

class ParametrosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('parametros')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Parametro::create([
            'parametro'=>'MaxStudentSize',
            'valor'=>'2',
        ]);
        Parametro::create([
            'parametro'=>'AvancesTesisFormato',
            'valor'=>'.pdf',
        ]);
        Parametro::create([
            'parametro'=>'AvancesTesisFormato',
            'valor'=>'.docx',
        ]);
        Parametro::create([
            'parametro'=>'ActaFormato',
            'valor'=>'.pdf',
        ]);
        Parametro::create([
            'parametro'=>'ActaSize',
            'valor'=>'10',
        ]);
        Parametro::create([
            'parametro'=>'AvancesTesisSize',
            'valor'=>'50',
        ]);
    }
}
