<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RolePermissionSecretariaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles_permissions')->insert([
            'id_role' => 4,
            'id_permission' => 1
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 4,
            'id_permission' => 43
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 4,
            'id_permission' => 44
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 4,
            'id_permission' => 45
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 4,
            'id_permission' => 46
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 4,
            'id_permission' => 49
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 4,
            'id_permission' => 50
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 4,
            'id_permission' => 61
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 4,
            'id_permission' => 62
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 4,
            'id_permission' => 69
        ]);
        
    }
}
