<?php

use Illuminate\Database\Seeder;
use App\Fit;

class FitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('fit')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Fit::create([
        //     'id'=>'1',
        //     'id_vinculacion'=>'1',
        //     'id_p_guia' => '2',
        //     'id_p_co_guia' => '4',
        //     'estado' => 'D',
        //     'titulo' => 'Titulo de tesis',
        //     'tipo' => 'tesis',
        //     'objetivo_general' => 'Objetivo general de tesis 123412341234',
        //     'objetivo_especifico' => 'Objetivo específico de tesis 123412341234',
        //     'descripcion' => 'Descripcion de tesis',
        //     'contribucion' => 'Contribucion de tesis',
        //     'aprobado_pg' => 'P'
        // ]);

        //factory(Fit::class, 10)->create();
    }
}
