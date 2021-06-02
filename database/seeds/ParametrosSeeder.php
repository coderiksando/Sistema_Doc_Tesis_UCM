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
        Parametro::create([
            'parametro'=>'AvancesTesisFormato',
            'valor'=>'.pdf',
        ]);
        Parametro::create([
            'parametro'=>'AvancesTesisFormato',
            'valor'=>'.docx',
        ]);
    }
}
