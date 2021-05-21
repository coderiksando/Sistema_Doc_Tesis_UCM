<?php

use Illuminate\Database\Seeder;
use App\AvancesTesis;

class AvancesTesisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('avancestesis')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        factory(AvancesTesis::class, 100)->create();
    }
}
