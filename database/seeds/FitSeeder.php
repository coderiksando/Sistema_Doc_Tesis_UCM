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

        Fit::create([
            'id'=>'1',
            'id_vinculacion'=>'1',
            'id_p_guia' => '2',
            'estado' => 'D',
            'titulo' => 'La tesis mas chingona del mundo',
            'tipo' => 'tesis',
            'objetivo' => 'hacer del mundo un lugar mejor',
            'descripcion' => 'no puedo, es ultrasecreto',
            'contribucion' => 'obvio yo pago todos mis impuestos',
            'aprobado_pg' => 'P'
        ]);
    }
}
