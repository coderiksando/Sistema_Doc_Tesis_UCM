<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EscuelasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('escuelas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('escuelas')->insert([
            'id'=>'1',
            'id_facultad'=>'1',
            'nombre'=>'INGENIERIA CIVIL INFORMATICA'
        ]);
        DB::table('escuelas')->insert([
            'id'=>'2',
            'id_facultad'=>'1',
            'nombre'=>'ARQUITECTURA'
        ]);
        DB::table('escuelas')->insert([
            'id'=>'3',
            'id_facultad'=>'1',
            'nombre'=>'INGENIERIA CIVIL'
        ]);
        DB::table('escuelas')->insert([
            'id'=>'4',
            'id_facultad'=>'1',
            'nombre'=>'CONTRUCCION CIVIL'
        ]);
        DB::table('escuelas')->insert([
            'id'=>'5',
            'id_facultad'=>'1',
            'nombre'=>'INGENIERIA CIVIL INDUSTRIAL'
        ]);
        DB::table('escuelas')->insert([
            'id'=>'6',
            'id_facultad'=>'1',
            'nombre'=>'INGENIERIA CIVIL ELECTRONICA'
        ]);
        DB::table('escuelas')->insert([
            'id'=>'7',
            'id_facultad'=>'1',
            'nombre'=>'INGENIERIA EN CONTRUCCION'
        ]);
    }
}
