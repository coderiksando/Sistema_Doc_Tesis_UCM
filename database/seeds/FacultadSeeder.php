<?php

use Illuminate\Database\Seeder;

class FacultadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('facultad')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('facultad')->insert([
            'id'=>'1',
            'nombre'=>'Facultad de Ciencias de la Ingeniería'
        ]);
    }
}
