<?php

use Illuminate\Database\Seeder;
use App\Bitacoras;

class BitacorasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bitacoras')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //factory(Bitacoras::class, 50)->create();
    }
}
