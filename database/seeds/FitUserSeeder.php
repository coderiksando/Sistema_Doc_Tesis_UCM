<?php

use Illuminate\Database\Seeder;
use App\Fit_User;
use App\Roles;
use App\Fit;

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

        // Fit_User::create([
        //     'id_user' => '5',
        //     'id_fit' => '1'
        // ]);
        // Fit_User::create([
        //     'id_user' => '7',
        //     'id_fit' => '1'
        // ]);

        // foreach (Roles::find(2)->Users_Roles->where('id_user', '<>', '5') as $user) {
        //     Fit_User::create([
        //         'id_user' => $user->id_user,
        //         'id_fit' => Fit::all()->skip(1)->random()->id
        //     ]);
        // }
    }
}
