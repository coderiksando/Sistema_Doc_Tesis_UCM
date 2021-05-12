<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users_roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('users_roles')->insert([
            'id_user' => 1,
            'id_roles' => 1
        ]);
        DB::table('users_roles')->insert([
            'id_user' => 2,
            'id_roles' => 3
        ]);
        DB::table('users_roles')->insert([
            'id_user' => 3,
            'id_roles' => 6
        ]);
        DB::table('users_roles')->insert([
            'id_user' => 4,
            'id_roles' => 5
        ]);
        DB::table('users_roles')->insert([
            'id_user' => 5,
            'id_roles' => 2
        ]);
        DB::table('users_roles')->insert([
            'id_user' => 7,
            'id_roles' => 2
        ]);
        DB::table('users_roles')->insert([
            'id_user' => 6,
            'id_roles' => 4
        ]);
    }
}
