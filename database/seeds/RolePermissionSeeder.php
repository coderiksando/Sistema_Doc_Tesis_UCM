<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 1
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 2
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 3
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 4
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 5
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 6
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 7
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 8
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 9
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 10
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 11
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 12
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 13
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 14
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 15
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 16
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 17
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 18
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 19
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 20
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 21
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 25
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 30
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 39
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 42
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 52
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 53
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 55
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 56
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 57
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 58
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 59
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 61
        ]);
        DB::table('roles_permissions')->insert([
            'id_role' => 1,
            'id_permission' => 62
        ]);
    }
}
