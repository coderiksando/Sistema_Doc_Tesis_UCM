<?php

use Illuminate\Database\Seeder;
use App\Fit_User;

class FitUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('fit_user')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Fit_User::create([
            'id_user' => '5',
            'id_fit' => '1'
        ]);
        Fit_User::create([
            'id_user' => '7',
            'id_fit' => '1'
        ]);
    }
}
